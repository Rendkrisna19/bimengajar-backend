<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ulasan;

class UlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ulasanList = [
            [
                'nama' => 'I GUSTI AGUNG PUTRA',
                'kategori' => 'Pelajar',
                'instansi' => 'SMPN 2 DENPASAR',
                'komentar' => 'Menurut saya lomba ini sangat seru, mengedukasi, dan melatih kemampuan menghafal saya. Saya harap kedepannya lebih sering diadakan.',
                'rating' => 5
            ],
            [
                'nama' => 'Steven',
                'kategori' => 'Pelajar',
                'instansi' => 'SMP',
                'komentar' => 'Lomba yang menarik, wawasan saya tentang kebanksentralan bertambah.',
                'rating' => 5
            ],
            [
                'nama' => 'Putu Nayla Anggita Cahyani',
                'kategori' => 'Pelajar',
                'instansi' => 'SMP Negeri 10 Denpasar',
                'komentar' => 'Alur lomba yang menarik, materi lengkap, dan penyampaian narasumber sangat interaktif.',
                'rating' => 5
            ],
            [
                'nama' => 'Budi Santoso',
                'kategori' => 'Guru / Tenaga Pendidik',
                'instansi' => 'SMA Negeri 1',
                'komentar' => 'Program BI Mengajar memberikan dampak positif yang luar biasa bagi pemahaman ekonomi para siswa.',
                'rating' => 5
            ],
            [
                'nama' => 'Siti Aminah',
                'kategori' => 'Mahasiswa',
                'instansi' => 'Universitas Simalungun',
                'komentar' => 'Sosialisasi QRIS dan CBP Rupiah sangat bermanfaat dan relevan dengan dunia kerja.',
                'rating' => 5
            ]
        ];

        foreach ($ulasanList as $ulasan) {
            Ulasan::create($ulasan);
        }
    }
}
