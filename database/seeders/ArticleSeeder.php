<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Bangun Generasi Cinta Rupiah, Bank Indonesia Hadirkan Edukasi Interaktif',
                'author' => 'UIPUR',
                'image' => ['https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=400&h=300&auto=format&fit=crop'],
                'description' => 'Bank Indonesia terus berupaya membangun kesadaran generasi muda akan pentingnya menjaga dan merawat uang Rupiah.',
                'content' => 'Full content here...',
                'published_at' => Carbon::parse('2026-07-20 10:00:00'),
            ],
            [
                'title' => 'MPLS Jadi Momentum Menanamkan Cinta Rupiah Sejak Dini',
                'author' => 'UIPUR',
                'image' => ['https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=400&h=300&auto=format&fit=crop'],
                'description' => 'Masa Pengenalan Lingkungan Sekolah (MPLS) dimanfaatkan Bank Indonesia untuk memberikan pemahaman Cinta Bangga Paham Rupiah.',
                'content' => 'Full content here...',
                'published_at' => Carbon::parse('2026-07-20 11:30:00'),
            ],
            [
                'title' => 'WALAU RECEH, TERNYATA SANGAT BERARTI ⁉️',
                'author' => 'UIPUR',
                'image' => ['https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?q=80&w=400&h=300&auto=format&fit=crop'],
                'description' => 'Uang koin seringkali diremehkan, padahal keberadaannya sangat krusial dalam sistem pembayaran ritel.',
                'content' => 'Full content here...',
                'published_at' => Carbon::parse('2026-07-20 14:15:00'),
            ],
            [
                'title' => 'Edukasi Keuangan Digital bagi Siswa SMK di Siantar',
                'author' => 'Admin',
                'image' => ['https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=400&h=300&auto=format&fit=crop'],
                'description' => 'Selain edukasi Rupiah fisik, Bank Indonesia juga gencar melakukan sosialisasi keuangan digital dan QRIS.',
                'content' => 'Full content here...',
                'published_at' => Carbon::parse('2026-07-18 09:00:00'),
            ],
            [
                'title' => 'Sinergi BI dan Guru Penggerak dalam Sosialisasi CBP',
                'author' => 'UIPUR',
                'image' => ['https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=400&h=300&auto=format&fit=crop'],
                'description' => 'Kolaborasi dengan Guru Penggerak menjadi salah satu strategi efektif memperluas jangkauan edukasi kebanksentralan.',
                'content' => 'Full content here...',
                'published_at' => Carbon::parse('2026-07-15 13:45:00'),
            ]
        ];

        foreach ($articles as $article) {
            $article['slug'] = Str::slug($article['title']);
            Article::updateOrCreate(['slug' => $article['slug']], $article);
        }
    }
}
