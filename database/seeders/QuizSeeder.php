<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Quiz 1: Kebanksentralan & Rupiah
        $quiz1 = Quiz::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Master Kebanksentralan & Rupiah',
                'category' => 'Kebanksentralan',
                'description' => 'Uji wawasanmu tentang tugas utama Bank Indonesia, stabilitas moneter, serta sejarah pecahan uang Rupiah.',
                'difficulty' => 'Sedang',
                'mode' => 'both',
                'estimated_time_minutes' => 5,
                'icon' => 'fa-solid fa-building-columns',
                'color' => 'from-blue-600 to-indigo-700',
                'is_active' => true,
            ]
        );

        $questions1 = [
            [
                'question_text' => 'Apa tujuan tunggal Bank Indonesia menurut UU No. 23 Tahun 1999 yang telah disempurnakan?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Mencetak uang sebanyak-banyaknya', 'is_correct' => false, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Mencapai dan memelihara kestabilan nilai Rupiah', 'is_correct' => true, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Memberikan pinjaman langsung kepada masyarakat', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Mengatur pajak nasional', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'Tujuan tunggal BI adalah mencapai dan memelihara kestabilan nilai Rupiah (laju inflasi & nilai tukar).',
                'sort_order' => 1,
            ],
            [
                'question_text' => 'Siapakah Gubernur Bank Indonesia yang menjabat saat ini (Periode 2018–2028)?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Agus Martowardojo', 'is_correct' => false, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Perry Warjiyo', 'is_correct' => true, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Sri Mulyani Indrawati', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Darmin Nasution', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'Perry Warjiyo merupakan Gubernur Bank Indonesia yang menjabat sejak 2018.',
                'sort_order' => 2,
            ],
            [
                'question_text' => 'Pilar utama apa saja yang menjadi kerangka kerja Bank Indonesia?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Moneter, Sistem Pembayaran, & Stabilitas Sistem Keuangan', 'is_correct' => true, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Pajak, Ekspor, & Impor', 'is_correct' => false, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Kredit Mikro, Asuransi, & Pegadaian', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Pasar Modal, Saham, & Kripto', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'Tiga pilar BI: Menetapkan & melaksanakan kebijakan moneter, Mengatur & menjaga kelancaran sistem pembayaran, serta Mengatur & mengawasi makroprudensial.',
                'sort_order' => 3,
            ],
            [
                'question_text' => 'Sebutkan 3 prinsip utama dalam kampanye Cinta, Bangga, Paham (CBP) Rupiah!',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Mengenali, Merawat, & Menyimpan', 'is_correct' => false, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Cinta (Merawat), Bangga (Simbol Negara), Paham (Bijak Berbelanja)', 'is_correct' => true, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Dilihat, Diraba, Diterawang', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Tarik, Setor, Transfer', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'CBP Rupiah mengajarkan Cinta Rupiah dengan 3D (Dilihat, Diraba, Diterawang), Bangga Rupiah sebagai pemersatu bangsa, dan Paham Rupiah dengan bertransaksi secara bijak.',
                'sort_order' => 4,
            ]
        ];

        foreach ($questions1 as $qData) {
            QuizQuestion::updateOrCreate(
                ['quiz_id' => $quiz1->id, 'sort_order' => $qData['sort_order']],
                $qData
            );
        }

        // 2. Quiz 2: QRIS & Digital Payment BI
        $quiz2 = Quiz::updateOrCreate(
            ['id' => 2],
            [
                'title' => 'Kuis QRIS & Digital Payment BI',
                'category' => 'Sistem Pembayaran',
                'description' => 'Asah pengetahuanmu mengenai QRIS, BI-FAST, dan transaksi nontunai di Indonesia.',
                'difficulty' => 'Mudah',
                'mode' => 'both',
                'estimated_time_minutes' => 4,
                'icon' => 'fa-solid fa-qrcode',
                'color' => 'from-emerald-600 to-teal-700',
                'is_active' => true,
            ]
        );

        $questions2 = [
            [
                'question_text' => 'Apa slogan utama QRIS yang dicanangkan oleh Bank Indonesia?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Satu QR untuk Semua', 'is_correct' => false, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Cepat, Murah, Mudah, Aman, dan Handal (CUMUDAH)', 'is_correct' => true, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Bayar Tanpa Biaya', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Semua Bisa Pakai Digital', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'Semangat QRIS dikemas dalam prinsip CeMuDaH: Cepat, Murah, Mudah, Aman, Handal.',
                'sort_order' => 1,
            ],
            [
                'question_text' => 'Fitur QRIS TUNTAS memungkinkan pengguna untuk melakukan apa saja?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Tarik Tunai, Transfer, & Setor Tunai', 'is_correct' => true, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Pinjaman Online & Kredivo', 'is_correct' => false, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Beli Saham & Kripto', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Bayar Pajak Impor', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'QRIS TUNTAS mempermudah layanan Tarik Tunai, Transfer, dan Setor Tunai antarpengguna.',
                'sort_order' => 2,
            ],
            [
                'question_text' => 'Kapan QRIS resmi diluncurkan secara nasional oleh Bank Indonesia?',
                'options' => [
                    ['id' => 'opt-1', 'text' => '17 Agustus 2019', 'is_correct' => true, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => '1 Januari 2020', 'is_correct' => false, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => '10 November 2018', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => '15 Juli 2021', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'QRIS resmi diluncurkan tepat pada HUT Kemerdekaan RI ke-74 pada 17 Agustus 2019.',
                'sort_order' => 3,
            ],
            [
                'question_text' => 'Apa kepanjangan resmi dari singkatan QRIS?',
                'options' => [
                    ['id' => 'opt-1', 'text' => 'Quick Response Code Indonesian Standard', 'is_correct' => true, 'color' => 'red'],
                    ['id' => 'opt-2', 'text' => 'Quality Rapid Indonesian System', 'is_correct' => false, 'color' => 'blue'],
                    ['id' => 'opt-3', 'text' => 'Quick Real-time Interbank Settlement', 'is_correct' => false, 'color' => 'yellow'],
                    ['id' => 'opt-4', 'text' => 'Quantum Retail Indonesian Solution', 'is_correct' => false, 'color' => 'green'],
                ],
                'time_limit_seconds' => 15,
                'explanation' => 'QRIS singkatan dari Quick Response Code Indonesian Standard.',
                'sort_order' => 4,
            ]
        ];

        foreach ($questions2 as $qData) {
            QuizQuestion::updateOrCreate(
                ['quiz_id' => $quiz2->id, 'sort_order' => $qData['sort_order']],
                $qData
            );
        }

        // 3. Quiz Results Mock Initial Data
        QuizResult::updateOrCreate(
            ['id' => 1],
            [
                'quiz_id' => $quiz1->id,
                'quiz_title' => 'Master Kebanksentralan & Rupiah',
                'nickname' => 'Ahmad Rizky',
                'avatar' => 'fa-solid fa-user-ninja',
                'mode' => 'solo',
                'score' => 4200,
                'correct_answers' => 4,
                'total_questions' => 4,
                'created_at' => now()->subHours(2),
            ]
        );

        QuizResult::updateOrCreate(
            ['id' => 2],
            [
                'quiz_id' => $quiz2->id,
                'quiz_title' => 'Kuis QRIS & Digital Payment BI',
                'nickname' => 'Siti Nurhaliza',
                'avatar' => 'fa-solid fa-user-astronaut',
                'mode' => 'multiplayer',
                'score' => 3950,
                'correct_answers' => 4,
                'total_questions' => 4,
                'created_at' => now()->subHours(5),
            ]
        );

        QuizResult::updateOrCreate(
            ['id' => 3],
            [
                'quiz_id' => $quiz1->id,
                'quiz_title' => 'Master Kebanksentralan & Rupiah',
                'nickname' => 'Budi Santoso',
                'avatar' => 'fa-solid fa-user-tie',
                'mode' => 'solo',
                'score' => 3100,
                'correct_answers' => 3,
                'total_questions' => 4,
                'created_at' => now()->subDay(),
            ]
        );
    }
}
