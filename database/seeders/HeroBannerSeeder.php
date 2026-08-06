<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroBanner;

class HeroBannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => "Edukasi untuk\nIndonesia yang Maju",
                'title_en' => "Education for\nan Advanced Indonesia",
                'subtitle' => 'Belajar, berkolaborasi, dan berkontribusi bersama Bank Indonesia untuk masyarakat yang Cinta, Bangga, dan Paham Rupiah.',
                'subtitle_en' => 'Learn, collaborate, and contribute with Bank Indonesia for a society that Loves, is Proud of, and Understands the Rupiah.',
                'button_primary_text' => 'Ajukan Edukasi',
                'button_primary_text_en' => 'Request Education',
                'button_primary_url' => '/edukasi/pengajuan',
                'button_secondary_text' => 'Jelajahi Materi',
                'button_secondary_text_en' => 'Explore Materials',
                'button_secondary_url' => '/edukasi/materi-edukasi',
                'image' => '/images/banner/hero1.png',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => "Kenali & Pahami\nRupiah Kita",
                'title_en' => "Know & Understand\nOur Rupiah",
                'subtitle' => 'Tingkatkan literasi keuangan dan kenali ciri keaslian Rupiah demi menjaga kedaulatan ekonomi bangsa.',
                'subtitle_en' => 'Improve financial literacy and recognize the authenticity features of the Rupiah to maintain the nation\'s economic sovereignty.',
                'button_primary_text' => 'Ajukan Edukasi',
                'button_primary_text_en' => 'Request Education',
                'button_primary_url' => '/edukasi/pengajuan',
                'button_secondary_text' => 'Jelajahi Materi',
                'button_secondary_text_en' => 'Explore Materials',
                'button_secondary_url' => '/edukasi/materi-edukasi',
                'image' => '/images/banner/hero2.png',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => "Layanan Penukaran\nUang Logam",
                'title_en' => "Coin Exchange\nServices",
                'subtitle' => 'Gunakan platform Pojok Koin untuk menukarkan uang logam dengan mudah dan bantu sirkulasi koin di masyarakat.',
                'subtitle_en' => 'Use the Coin Corner platform to easily exchange coins and help coin circulation in the community.',
                'button_primary_text' => 'Cari Lokasi Penukaran',
                'button_primary_text_en' => 'Find Location',
                'button_primary_url' => '/titik-temu',
                'button_secondary_text' => 'Jelajahi Materi',
                'button_secondary_text_en' => 'Explore Materials',
                'button_secondary_url' => '/edukasi/materi-edukasi',
                'image' => '/images/banner/hero3.png',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            HeroBanner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
