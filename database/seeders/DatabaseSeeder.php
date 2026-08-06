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
        $this->call([
            AdminSeeder::class,
            KategoriMateriSeeder::class,
            EdukasiLocationSeeder::class,
            ArticleSeeder::class,
            MateriEdukasiSeeder::class,
            UlasanSeeder::class,
            NewsSeeder::class,
            AboutSeeder::class,
        ]);
    }
}
