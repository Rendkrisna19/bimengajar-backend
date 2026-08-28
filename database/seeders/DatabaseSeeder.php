<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hanya jalankan AdminSeeder saat `php artisan db:seed`
        // Seeder konten/dummy lainnya dinonaktifkan agar tidak menimpa data di server.
        $this->call([
            AdminSeeder::class,
            /*
            // Jalankan seeder di bawah secara spesifik jika dibutuhkan:
            // php artisan db:seed --class=NamaSeeder
            KategoriMateriSeeder::class,
            EdukasiLocationSeeder::class,
            ArticleSeeder::class,
            MateriEdukasiSeeder::class,
            UlasanSeeder::class,
            NewsSeeder::class,
            AboutSeeder::class,
            HeroBannerSeeder::class,
            DokumentasiSeeder::class,
            QuizSeeder::class,
            */
        ]);
    }
}
