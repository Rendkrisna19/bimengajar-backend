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
                'deskripsi' => 'Kegiatan sosialisasi penggunaan QRIS bagi para pedagang di pasar tradisional untuk mendorong transaksi non-tunai dan inklusi keuangan.',
                'tanggal_kegiatan' => '2025-10-15',
                'posted_by' => 'Administrator BI',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1556742049-0a679246c5a7?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=800&q=80'
                ]),
                'video_urls' => json_encode(['https://www.youtube.com/watch?v=dQw4w9WgXcQ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Seminar Cinta Bangga Paham Rupiah',
                'kategori' => 'Seminar',
                'deskripsi' => 'Seminar yang dihadiri oleh ratusan mahasiswa untuk meningkatkan pemahaman mengenai 3D (Dilihat, Diteraba, Diterawang) dan ciri keaslian uang Rupiah.',
                'tanggal_kegiatan' => '2025-11-20',
                'posted_by' => 'Administrator BI',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=800&q=80'
                ]),
                'video_urls' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Kunjungan Edukasi Kebanksentralan',
                'kategori' => 'Kunjungan',
                'deskripsi' => 'Kunjungan siswa SMA ke Kantor Perwakilan Bank Indonesia Pematangsiantar untuk mempelajari tugas dan fungsi bank sentral.',
                'tanggal_kegiatan' => '2026-01-10',
                'posted_by' => 'Administrator BI',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80'
                ]),
                'video_urls' => json_encode(['https://www.youtube.com/watch?v=dQw4w9WgXcQ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Workshop Penukaran Koin & Kas Keliling',
                'kategori' => 'Workshop',
                'deskripsi' => 'Pelayanan kas keliling dan workshop edukasi transaksi penukaran koin rupiah untuk masyarakat umum.',
                'tanggal_kegiatan' => '2026-02-05',
                'posted_by' => 'Administrator BI',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=800&q=80'
                ]),
                'video_urls' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        \App\Models\Dokumentasi::insert($data);
    }
}
