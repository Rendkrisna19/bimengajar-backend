<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\QuizSession;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    // GET /api/quizzes - List all active quizzes with their questions
    public function index()
    {
        $quizzes = Quiz::with('questions')->where('is_active', true)->get();

        return response()->json([
            'status' => 'success',
            'data' => $quizzes
        ]);
    }

    // GET /api/quizzes/{id} - Detail single quiz
    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $quiz
        ]);
    }

    // POST /api/quizzes - Create quiz (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'difficulty' => 'required|string',
            'mode' => 'required|string',
            'estimated_time_minutes' => 'integer',
            'questions' => 'nullable|array',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? '',
            'difficulty' => $validated['difficulty'],
            'mode' => $validated['mode'],
            'estimated_time_minutes' => $validated['estimated_time_minutes'] ?? 5,
            'icon' => 'fa-solid fa-gamepad',
            'color' => 'from-sky-500 to-blue-600',
            'is_active' => true,
        ]);

        if (!empty($validated['questions'])) {
            foreach ($validated['questions'] as $idx => $q) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $q['question_text'],
                    'options' => $q['options'],
                    'time_limit_seconds' => $q['time_limit_seconds'] ?? 15,
                    'explanation' => $q['explanation'] ?? null,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil dibuat',
            'data' => $quiz->load('questions')
        ], 201);
    }

    // PUT /api/quizzes/{id} - Update quiz (Admin)
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'difficulty' => 'required|string',
            'mode' => 'required|string',
            'estimated_time_minutes' => 'integer',
            'questions' => 'nullable|array',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? '',
            'difficulty' => $validated['difficulty'],
            'mode' => $validated['mode'],
            'estimated_time_minutes' => $validated['estimated_time_minutes'] ?? 5,
        ]);

        if (isset($validated['questions'])) {
            $quiz->questions()->delete();
            foreach ($validated['questions'] as $idx => $q) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $q['question_text'],
                    'options' => $q['options'],
                    'time_limit_seconds' => $q['time_limit_seconds'] ?? 15,
                    'explanation' => $q['explanation'] ?? null,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil diperbarui',
            'data' => $quiz->load('questions')
        ]);
    }

    // DELETE /api/quizzes/{id} - Delete quiz (Admin)
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if ($quiz) {
            $quiz->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kuis berhasil dihapus'
        ]);
    }

    // GET /api/quiz-results - Leaderboard / History
    public function getResults()
    {
        $results = QuizResult::orderBy('score', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);
    }

    // POST /api/quiz-results - Save completion result
    public function storeResult(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'nullable|integer',
            'quiz_title' => 'required|string',
            'nickname' => 'required|string',
            'avatar' => 'nullable|string',
            'mode' => 'required|string',
            'score' => 'required|integer',
            'correct_answers' => 'required|integer',
            'total_questions' => 'required|integer',
            'pin_code' => 'nullable|string',
        ]);

        $result = QuizResult::create([
            'quiz_id' => $validated['quiz_id'] ?? null,
            'quiz_title' => $validated['quiz_title'],
            'user_id' => auth('sanctum')->id() ?? null,
            'nickname' => $validated['nickname'],
            'avatar' => $validated['avatar'] ?? 'fa-solid fa-user-astronaut',
            'mode' => $validated['mode'],
            'score' => $validated['score'],
            'correct_answers' => $validated['correct_answers'],
            'total_questions' => $validated['total_questions'],
            'pin_code' => $validated['pin_code'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hasil kuis berhasil disimpan',
            'data' => $result
        ], 201);
    }

    // DELETE /api/quiz-results - Clear score history (Admin)
    public function clearResults()
    {
        QuizResult::truncate();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat poin kuis berhasil dibersihkan'
        ]);
    }

    private function formatSession(QuizSession $session)
    {
        $session->loadMissing('quiz.questions');
        $quiz = $session->quiz;
        return [
            'id' => $session->id,
            'pin_code' => $session->pin_code,
            'quiz_id' => (string)$session->quiz_id,
            'quiz_title' => $quiz ? $quiz->title : 'Kuis Interaktif BI',
            'status' => $session->status,
            'current_question_index' => $session->current_question_index ?? 0,
            'host_name' => $session->host_name ?? 'Edukator BI',
            'participants' => $session->participants ?? [],
            'quiz' => $quiz,
        ];
    }

    // GET /api/quiz-sessions/active - Get current active live room
    public function getActiveLiveSession()
    {
        $session = QuizSession::whereIn('status', ['waiting', 'playing'])
            ->latest('id')
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 'success',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatSession($session)
        ]);
    }

    // GET /api/quiz-sessions/{pin} - Get live room by PIN
    public function getLiveSessionByPin($pin)
    {
        $session = QuizSession::where('pin_code', trim($pin))->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan live quiz tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatSession($session)
        ]);
    }

    // POST /api/quiz-sessions/create - Admin Launch Live Room (saves to DB table quiz_sessions)
    public function createLiveSession(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required',
            'pin_code' => 'nullable|string|size:6',
            'host_name' => 'nullable|string',
        ]);

        // Finish any previously active sessions first
        QuizSession::whereIn('status', ['waiting', 'playing'])->update(['status' => 'finished']);

        $pin = $validated['pin_code'] ?? str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $quiz = Quiz::find($validated['quiz_id']);

        $session = QuizSession::create([
            'quiz_id' => $quiz ? $quiz->id : 1,
            'pin_code' => $pin,
            'status' => 'waiting',
            'current_question_index' => 0,
            'participants' => [],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Live Room berhasil dibuka dan disimpan ke database',
            'data' => $this->formatSession($session)
        ], 201);
    }

    // POST /api/quiz-sessions/join - User Join Live Room (validates duplicate nickname in DB)
    public function joinLiveSession(Request $request)
    {
        $validated = $request->validate([
            'pin_code' => 'required|string',
            'nickname' => 'required|string|max:30',
            'avatar' => 'nullable|string',
        ]);

        $pin = trim($validated['pin_code']);
        $nickname = trim($validated['nickname']);
        $avatar = $validated['avatar'] ?? 'avatar-1';

        $session = QuizSession::where('pin_code', $pin)->latest('id')->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Game PIN tidak valid! Pastikan Anda memasukkan PIN 6-digit yang diberikan Host.'
            ], 404);
        }

        if ($session->status === 'finished') {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi live room ini sudah selesai. Minta Host untuk membuka sesi baru.'
            ], 400);
        }

        $participants = $session->participants ?? [];

        // Check duplicate nickname (case-insensitive)
        foreach ($participants as $p) {
            if (isset($p['nickname']) && strtolower(trim($p['nickname'])) === strtolower($nickname)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Nickname '{$nickname}' sudah dipakai oleh peserta lain di room ini! Silakan gunakan nama berbeda."
                ], 422);
            }
        }

        // Add new participant
        $participants[] = [
            'id' => 'p-' . time() . '-' . Str::random(5),
            'nickname' => $nickname,
            'avatar' => $avatar,
            'score' => 0,
            'streak' => 0,
        ];

        $session->participants = $participants;
        $session->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil bergabung ke Live Room',
            'data' => $this->formatSession($session)
        ]);
    }

    // POST /api/quiz-sessions/start - Host Start Game (updates DB status='playing')
    public function startLiveSessionGame(Request $request)
    {
        $pin = $request->input('pin_code');

        $query = QuizSession::query();
        if ($pin) {
            $query->where('pin_code', trim($pin));
        } else {
            $query->where('status', 'waiting');
        }

        $session = $query->latest('id')->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan live kuis tidak ditemukan atau sudah berjalan.'
            ], 404);
        }

        $session->status = 'playing';
        $session->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Live Game berhasil dimulai',
            'data' => $this->formatSession($session)
        ]);
    }

    // POST /api/quiz-sessions/score - Update participant score in live session
    public function updateParticipantScore(Request $request)
    {
        $validated = $request->validate([
            'pin_code' => 'required|string',
            'nickname' => 'required|string',
            'score' => 'required|integer',
            'streak' => 'nullable|integer',
        ]);

        $pin = trim($validated['pin_code']);
        $nickname = trim($validated['nickname']);
        $score = (int) $validated['score'];
        $streak = (int) ($validated['streak'] ?? 0);

        $session = QuizSession::where('pin_code', $pin)->latest('id')->first();
        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Sesi kuis tidak ditemukan'], 404);
        }

        $participants = $session->participants ?? [];
        $found = false;

        foreach ($participants as &$p) {
            if (isset($p['nickname']) && strtolower(trim($p['nickname'])) === strtolower($nickname)) {
                $p['score'] = $score;
                $p['streak'] = $streak;
                $found = true;
                break;
            }
        }

        if ($found) {
            $session->participants = $participants;
            $session->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Skor peserta berhasil diperbarui',
            'data' => $this->formatSession($session)
        ]);
    }

    // POST /api/quiz-sessions/close - Host Close Live Room (updates DB status='finished')
    public function closeLiveSession(Request $request)
    {
        $pin = $request->input('pin_code');

        $query = QuizSession::query();
        if ($pin) {
            $query->where('pin_code', trim($pin));
        } else {
            $query->whereIn('status', ['waiting', 'playing']);
        }

        $session = $query->latest('id')->first();

        if ($session) {
            $session->status = 'finished';
            $session->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Live Room berhasil ditutup di database'
        ]);
    }
}
