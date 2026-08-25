<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrePostTest;
use App\Models\TestSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PrePostTestController extends Controller
{
    private function ensureTablesExist()
    {
        try {
            if (!Schema::hasTable('pre_post_tests')) {
                Schema::create('pre_post_tests', function (Blueprint $table) {
                    $table->id();
                    $table->string('judul');
                    $table->enum('tipe', ['pre-test', 'post-test'])->default('pre-test');
                    $table->text('deskripsi')->nullable();
                    $table->json('slides')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('test_submissions')) {
                Schema::create('test_submissions', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('test_id')->nullable();
                    $table->string('nama_peserta');
                    $table->string('instansi')->nullable();
                    $table->string('email')->nullable();
                    $table->date('tanggal_bi_mengajar')->nullable();
                    $table->integer('skor_total')->default(0);
                    $table->integer('skor_maksimal')->default(100);
                    $table->json('detail_jawaban')->nullable();
                    $table->timestamp('waktu_selesai')->nullable();
                    $table->timestamps();
                });
            } else {
                if (!Schema::hasColumn('test_submissions', 'tanggal_bi_mengajar')) {
                    Schema::table('test_submissions', function (Blueprint $table) {
                        $table->date('tanggal_bi_mengajar')->nullable()->after('email');
                    });
                }
            }
        } catch (\Exception $e) {
            // Ignore schema creation errors if already present
        }
    }

    private function getDefaultSampleTests()
    {
        return [
            [
                'id' => 1,
                'judul' => 'Pre-Test Literasi Keuangan & Kebanksentralan',
                'tipe' => 'pre-test',
                'deskripsi' => 'Uji pemahaman awal Anda mengenai Bank Indonesia, ciri keaslian Rupiah, dan transaksi pembayaran non-tunai.',
                'is_active' => true,
                'slides' => [
                    [
                        'id' => 'slide-1',
                        'judul_slide' => 'Slide 1: Pengenalan Tugas & Peran Bank Indonesia',
                        'deskripsi_slide' => 'Pahami tugas utama Bank Sentral Republik Indonesia.',
                        'soal' => [
                            [
                                'id' => 'q1',
                                'pertanyaan' => 'Tujuan utama Bank Indonesia sebagaimana diamanatkan undang-undang adalah...',
                                'skor' => 25,
                                'pilihan' => [
                                    'A. Mencapai dan memelihara kestabilan nilai Rupiah',
                                    'B. Membuka rekening tabungan ritel untuk perorangan',
                                    'C. Menghimpun dana deposito berjangka masyarakat',
                                    'D. Menyalurkan kredit perumahan rakyat (KPR)'
                                ],
                                'kunci_jawaban' => 'A. Mencapai dan memelihara kestabilan nilai Rupiah'
                            ],
                            [
                                'id' => 'q2',
                                'pertanyaan' => 'Mana di antara berikut yang merupakan tiga pilar utama tugas Bank Indonesia?',
                                'skor' => 25,
                                'pilihan' => [
                                    'A. Kebijakan Moneter, Sistem Pembayaran, dan Stabilitas Sistem Keuangan',
                                    'B. Perpajakan Negara, Pasar Saham, dan Ekspor Impor',
                                    'C. Tabungan Pegawai, Asuransi Kesehatan, dan Koperasi',
                                    'D. Audit BPK, Anggaran APBN, dan Bea Cukai'
                                ],
                                'kunci_jawaban' => 'A. Kebijakan Moneter, Sistem Pembayaran, dan Stabilitas Sistem Keuangan'
                            ]
                        ]
                    ],
                    [
                        'id' => 'slide-2',
                        'judul_slide' => 'Slide 2: Cinta, Bangga, dan Paham Rupiah (CBP)',
                        'deskripsi_slide' => 'Materi seputar perlakuan Rupiah dan sistem pembayaran non-tunai.',
                        'soal' => [
                            [
                                'id' => 'q3',
                                'pertanyaan' => 'Metode 3D untuk mengenali keaslian uang Rupiah kertas adalah...',
                                'skor' => 25,
                                'pilihan' => [
                                    'A. Dilihat, Diraba, Diterawang',
                                    'B. Dicuci, Dicuplik, Diukur',
                                    'C. Difoto, Disimpan, Dilipat',
                                    'D. Ditukar, Dihitung, Diarsip'
                                ],
                                'kunci_jawaban' => 'A. Dilihat, Diraba, Diterawang'
                            ],
                            [
                                'id' => 'q4',
                                'pertanyaan' => 'Standar kode QR nasional yang digagas oleh Bank Indonesia untuk pembayaran digital adalah...',
                                'skor' => 25,
                                'pilihan' => [
                                    'A. QRIS (Quick Response Code Indonesian Standard)',
                                    'B. BI-FAST Pay',
                                    'C. Barcode e-KTP',
                                    'D. Quick Pay Mandiri'
                                ],
                                'kunci_jawaban' => 'A. QRIS (Quick Response Code Indonesian Standard)'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'id' => 2,
                'judul' => 'Post-Test Evaluasi Pembelajaran BI Mengajar',
                'tipe' => 'post-test',
                'deskripsi' => 'Evaluasi peningkatan pemahaman Anda setelah menyelesaikan materi edukasi kebanksentralan.',
                'is_active' => true,
                'slides' => [
                    [
                        'id' => 'slide-1',
                        'judul_slide' => 'Slide 1: Evaluasi Pemahaman Materi Edukasi',
                        'deskripsi_slide' => 'Jawablah soal-soal berikut dengan teliti.',
                        'soal' => [
                            [
                                'id' => 'q101',
                                'pertanyaan' => 'Apakah logo Bank Indonesia wajib tertera pada setiap uang kertas Rupiah resmi?',
                                'skor' => 50,
                                'pilihan' => [
                                    'A. Ya, beserta tanda tangan Gubernur BI dan Menteri Keuangan',
                                    'B. Tidak wajib',
                                    'C. Hanya di uang pecahan logam saja',
                                    'D. Tergantung tahun emisi'
                                ],
                                'kunci_jawaban' => 'A. Ya, beserta tanda tangan Gubernur BI dan Menteri Keuangan'
                            ],
                            [
                                'id' => 'q102',
                                'pertanyaan' => 'Salah satu bentuk perilaku Cinta Rupiah adalah...',
                                'skor' => 50,
                                'pilihan' => [
                                    'A. Merawat uang dengan tidak meremas, membasahi, atau merusak',
                                    'B. Membakar uang lusuh yang sudah tidak terpakai',
                                    'C. Menjual uang Rupiah di atas harga nominal secara ilegal',
                                    'D. Menyimpan uang kertas dalam keadaan basah'
                                ],
                                'kunci_jawaban' => 'A. Merawat uang dengan tidak meremas, membasahi, atau merusak'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $this->ensureTablesExist();

        try {
            $tests = PrePostTest::orderBy('created_at', 'desc')->get();
            if ($tests->isEmpty()) {
                // Auto seed initial data if database table is empty
                foreach ($this->getDefaultSampleTests() as $sample) {
                    PrePostTest::create([
                        'judul' => $sample['judul'],
                        'tipe' => $sample['tipe'],
                        'deskripsi' => $sample['deskripsi'],
                        'slides' => $sample['slides'],
                        'is_active' => $sample['is_active'],
                    ]);
                }
                $tests = PrePostTest::orderBy('created_at', 'desc')->get();
            }

            // Public filter active only unless admin
            if (!$request->boolean('admin') && !$request->header('Authorization')) {
                $tests = $tests->where('is_active', true)->values();
            }

            return response()->json([
                'status' => 'success',
                'data' => $tests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'success',
                'data' => $this->getDefaultSampleTests()
            ]);
        }
    }

    public function show($id)
    {
        $this->ensureTablesExist();
        try {
            $test = PrePostTest::find($id);
            if (!$test) {
                $samples = $this->getDefaultSampleTests();
                $test = collect($samples)->firstWhere('id', (int)$id) ?: $samples[0];
            }
            return response()->json([
                'status' => 'success',
                'data' => $test
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->ensureTablesExist();
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:pre-test,post-test',
            'deskripsi' => 'nullable|string',
            'slides' => 'required|array',
            'is_active' => 'nullable|boolean',
        ]);

        $test = PrePostTest::create([
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'deskripsi' => $validated['deskripsi'] ?? '',
            'slides' => $validated['slides'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tes berhasil dibuat',
            'data' => $test
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $this->ensureTablesExist();
        $test = PrePostTest::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'tipe' => 'sometimes|required|in:pre-test,post-test',
            'deskripsi' => 'nullable|string',
            'slides' => 'sometimes|required|array',
            'is_active' => 'nullable|boolean',
        ]);

        $test->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Tes berhasil diperbarui',
            'data' => $test
        ]);
    }

    public function destroy($id)
    {
        $this->ensureTablesExist();
        $test = PrePostTest::findOrFail($id);
        $test->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tes berhasil dihapus'
        ]);
    }

    public function submitTest(Request $request, $id)
    {
        $this->ensureTablesExist();

        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'tanggal_bi_mengajar' => 'nullable|date',
            'jawaban_user' => 'required|array', // key: question_id, value: selected answer string
        ]);

        $test = PrePostTest::find($id);
        $slides = $test ? $test->slides : null;

        if (!$slides) {
            $samples = $this->getDefaultSampleTests();
            $found = collect($samples)->firstWhere('id', (int)$id) ?: $samples[0];
            $slides = $found['slides'];
        }

        $totalScore = 0;
        $maxScore = 0;
        $evaluasiSoal = [];

        foreach ($slides as $slide) {
            if (isset($slide['soal']) && is_array($slide['soal'])) {
                foreach ($slide['soal'] as $soal) {
                    $qId = $soal['id'];
                    $scoreValue = isset($soal['skor']) ? (int)$soal['skor'] : 10;
                    $correctAnswer = trim($soal['kunci_jawaban'] ?? '');
                    $userAnswer = trim($validated['jawaban_user'][$qId] ?? '');

                    $maxScore += $scoreValue;
                    $isCorrect = false;

                    if (!empty($userAnswer) && !empty($correctAnswer)) {
                        // Compare exact answer or answer prefix
                        if ($userAnswer === $correctAnswer || strtolower($userAnswer) === strtolower($correctAnswer)) {
                            $isCorrect = true;
                        } elseif (substr($userAnswer, 0, 2) === substr($correctAnswer, 0, 2)) {
                            $isCorrect = true;
                        }
                    }

                    if ($isCorrect) {
                        $totalScore += $scoreValue;
                    }

                    $evaluasiSoal[] = [
                        'soal_id' => $qId,
                        'pertanyaan' => $soal['pertanyaan'],
                        'jawaban_peserta' => $userAnswer ?: '(Tidak dijawab)',
                        'kunci_jawaban' => $correctAnswer,
                        'is_benar' => $isCorrect,
                        'skor_diperoleh' => $isCorrect ? $scoreValue : 0,
                        'skor_maksimal' => $scoreValue,
                    ];
                }
            }
        }

        $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;

        $submission = TestSubmission::create([
            'test_id' => $test ? $test->id : $id,
            'nama_peserta' => $validated['nama_peserta'],
            'instansi' => $validated['instansi'] ?? 'Umum',
            'email' => $validated['email'] ?? null,
            'tanggal_bi_mengajar' => $validated['tanggal_bi_mengajar'] ?? now()->toDateString(),
            'skor_total' => $totalScore,
            'skor_maksimal' => $maxScore,
            'detail_jawaban' => $evaluasiSoal,
            'waktu_selesai' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tes berhasil diselesaikan',
            'data' => [
                'submission_id' => $submission->id,
                'nama_peserta' => $submission->nama_peserta,
                'tanggal_bi_mengajar' => $submission->tanggal_bi_mengajar,
                'skor_total' => $totalScore,
                'skor_maksimal' => $maxScore,
                'persentase' => $percentage,
                'kategori_hasil' => $percentage >= 80 ? 'Sangat Baik' : ($percentage >= 60 ? 'Baik' : 'Perlu Tingkatkan Pemahaman'),
                'detail_evaluasi' => $evaluasiSoal,
            ]
        ]);
    }

    public function getSubmissions(Request $request)
    {
        $this->ensureTablesExist();

        $query = TestSubmission::with('test')->orderBy('created_at', 'desc');

        if ($request->has('test_id') && !empty($request->test_id)) {
            $query->where('test_id', $request->test_id);
        }

        if ($request->has('tanggal') && !empty($request->tanggal)) {
            $query->whereDate('tanggal_bi_mengajar', $request->tanggal);
        }

        $submissions = $query->get();

        $totalSubmissions = $submissions->count();
        $avgScore = $totalSubmissions > 0 ? round($submissions->avg('skor_total'), 1) : 0;
        $lulusCount = $submissions->filter(function ($s) {
            return ($s->skor_maksimal > 0 ? ($s->skor_total / $s->skor_maksimal) : 0) >= 0.6;
        })->count();

        return response()->json([
            'status' => 'success',
            'summary' => [
                'total_peserta' => $totalSubmissions,
                'rata_rata_skor' => $avgScore,
                'total_lulus' => $lulusCount,
                'persentase_lulus' => $totalSubmissions > 0 ? round(($lulusCount / $totalSubmissions) * 100) : 0,
            ],
            'data' => $submissions
        ]);
    }

    public function deleteSubmission($id)
    {
        $this->ensureTablesExist();
        $submission = TestSubmission::findOrFail($id);
        $submission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data hasil tes berhasil dihapus'
        ]);
    }
}
