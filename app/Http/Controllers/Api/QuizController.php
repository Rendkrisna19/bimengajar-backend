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

    // POST /api/quiz-sessions/create - Admin Launch Live Room
    public function createLiveSession(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|integer',
        ]);

        $pin = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $session = QuizSession::create([
            'quiz_id' => $validated['quiz_id'],
            'pin_code' => $pin,
            'status' => 'waiting',
            'current_question_index' => 0,
            'participants' => [],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Live Room berhasil dibuka',
            'data' => $session->load('quiz.questions')
        ]);
    }
}
