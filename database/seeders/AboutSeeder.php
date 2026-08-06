<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;
use Illuminate\Support\Facades\Cache;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        About::updateOrCreate(
            ['type' => 'tentang_bi'],
            [
                'title' => 'Tentang Bank Indonesia Siantar',
                'title_en' => 'About Bank Indonesia Siantar',
                'content' => 'Bank Indonesia Kantor Perwakilan Pematangsiantar berkomitmen untuk mendukung pertumbuhan ekonomi daerah, menjaga kestabilan nilai Rupiah, serta meningkatkan literasi kebanksentralan dan keuangan bagi masyarakat Pematangsiantar dan sekitarnya melalui program edukasi BI Mengajar.',
                'content_en' => 'Bank Indonesia Representative Office of Pematangsiantar is committed to supporting regional economic growth, maintaining the stability of the Rupiah currency, and enhancing central banking and financial literacy for the community of Pematangsiantar and surrounding areas through the BI Mengajar education program.',
            ]
        );

        Cache::forget('abouts_all');
    }
}
