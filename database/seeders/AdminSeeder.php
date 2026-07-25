<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        User::updateOrCreate(
            ['email' => 'admin@bi-mengajar.id'],
            [
                'name' => 'Administrator BI',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Seeder untuk User Biasa
        User::updateOrCreate(
            ['email' => 'user@bi-mengajar.id'],
            [
                'name' => 'User Instansi',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
