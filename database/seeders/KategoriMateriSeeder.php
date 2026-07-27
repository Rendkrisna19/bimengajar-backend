<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriMateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Kebanksentralan',
            'Sistem Pembayaran',
            'Rupiah',
            'QRIS',
            'CBP Rupiah',
            'Perlindungan Konsumen',
            'Lainnya'
        ];

        foreach ($kategoris as $kategori) {
            \App\Models\KategoriMateri::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($kategori)],
                ['nama' => $kategori]
            );
        }
    }
}
