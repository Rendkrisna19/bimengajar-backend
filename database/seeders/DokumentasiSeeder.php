<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokumentasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kegiatan' => 'Sosialisasi QRIS di Pasar Tradisional',
                'kategori' => 'Sosialisasi',
                'deskripsi' => 'Kegiatan sosialisasi penggunaan QRIS bagi para pedagang di pasar tradisional untuk mendorong transaksi non-tunai.',
                'tanggal_kegiatan' => '2023-10-15',
                'posted_by' => 'Administrator BI',
                'images' => json_encode(['/images/banner/hero1.png', '/images/banner/hero2.png']),
                'video_urls' => json_encode(['https://www.youtube.com/watch?v=dQw4w9WgXcQ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Seminar Cinta Bangga Paham Rupiah',
                'kategori' => 'Seminar',
                'deskripsi' => 'Seminar yang dihadiri oleh mahasiswa untuk meningkatkan pemahaman mengenai ciri keaslian uang Rupiah.',
                'tanggal_kegiatan' => '2023-11-20',
                'posted_by' => 'Administrator BI',
                'images' => json_encode(['/images/banner/hero2.png']),
                'video_urls' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Kunjungan Edukasi Kebanksentralan',
                'kategori' => 'Kunjungan',
                'deskripsi' => 'Kunjungan siswa SMA ke kantor perwakilan Bank Indonesia untuk mempelajari tugas dan fungsi bank sentral.',
                'tanggal_kegiatan' => '2024-01-10',
                'posted_by' => 'Administrator BI',
                'images' => json_encode(['/images/banner/hero3.png', '/images/banner/hero1.png']),
                'video_urls' => json_encode(['https://www.youtube.com/watch?v=1234567890']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\Dokumentasi::insert($data);
    }
}
