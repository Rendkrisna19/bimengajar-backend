<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newsItems = [
            [
                'title' => 'Sosialisasi QRIS di Pasar Horas Sukses Digelar',
                'description' => 'Bank Indonesia cabang Pematangsiantar sukses menggelar sosialisasi penggunaan QRIS bagi para pedagang di Pasar Horas untuk mendorong digitalisasi UMKM.',
                'content' => '<p>Kegiatan sosialisasi ini merupakan bagian dari upaya Bank Indonesia...</p>',
                'category' => 'berita'
            ],
            [
                'title' => 'Gubernur BI Resmikan Pojok Koin di Siantar',
                'description' => 'Dalam rangka memudahkan masyarakat menukarkan uang logam, Bank Indonesia meresmikan fasilitas Pojok Koin di beberapa titik strategis.',
                'content' => '<p>Fasilitas Pojok Koin ini diharapkan dapat meningkatkan sirkulasi uang logam...</p>',
                'category' => 'berita'
            ],
            [
                'title' => 'BI Mengajar Sapa Mahasiswa USI',
                'description' => 'Program BI Mengajar kembali hadir, kali ini menyapa ribuan mahasiswa Universitas Simalungun (USI) dengan tema Cinta Bangga Paham Rupiah.',
                'content' => '<p>Acara yang berlangsung meriah ini diisi dengan berbagai kuis edukatif...</p>',
                'category' => 'berita'
            ],
            [
                'title' => 'Sinergi BI dan Pemkot Pematangsiantar Kendalikan Inflasi',
                'description' => 'Tim Pengendalian Inflasi Daerah (TPID) bersama BI menggelar rapat koordinasi untuk menjaga stabilitas harga bahan pokok.',
                'content' => '<p>Kerja sama yang erat antar lembaga sangat diperlukan untuk menghadapi gejolak harga...</p>',
                'category' => 'berita'
            ],
            [
                'title' => 'Pelatihan Keuangan Digital Bagi Guru SMA',
                'description' => 'Puluhan guru SMA di Siantar mengikuti pelatihan literasi keuangan digital yang diselenggarakan oleh BI Institute.',
                'content' => '<p>Pelatihan ini bertujuan agar guru dapat meneruskan ilmu kepada para muridnya...</p>',
                'category' => 'berita'
            ]
        ];

        foreach ($newsItems as $item) {
            News::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']) . '-' . rand(1000, 9999),
                'author' => 'Admin BI',
                'description' => $item['description'],
                'content' => $item['content'],
                'category' => $item['category'],
                'image' => [], // Empty array for no image
                'published_at' => now()->subDays(rand(1, 30)), // Random date in the past month
            ]);
        }
    }
}
