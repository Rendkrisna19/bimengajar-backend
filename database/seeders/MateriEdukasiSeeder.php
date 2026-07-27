<?php

namespace Database\Seeders;

use App\Models\KategoriMateri;
use App\Models\MateriEdukasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MateriEdukasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = KategoriMateri::first();
        $kategoriId = $kategori ? $kategori->id : 1; // Fallback ke 1 jika belum ada

        MateriEdukasi::create([
            'kategori_materi_id' => $kategoriId,
            'judul' => 'Pengenalan Dasar Sistem Pembayaran',
            'slug' => Str::slug('Pengenalan Dasar Sistem Pembayaran') . '-' . uniqid(),
            'deskripsi_singkat' => 'Materi pengantar untuk memahami ekosistem sistem pembayaran di Indonesia.',
            'jenis_konten' => 'Artikel',
            'konten_teks' => '<p>Sistem pembayaran adalah sistem yang mencakup seperangkat aturan, lembaga, dan mekanisme yang dipakai untuk melaksanakan pemindahan dana...</p>',
            'link_youtube' => [],
            'link_drive' => [],
            'images' => []
        ]);

        MateriEdukasi::create([
            'kategori_materi_id' => $kategoriId,
            'judul' => 'Cara Aman Bertransaksi Menggunakan QRIS',
            'slug' => Str::slug('Cara Aman Bertransaksi Menggunakan QRIS') . '-' . uniqid(),
            'deskripsi_singkat' => 'Langkah-langkah dan tips keamanan dalam menggunakan QRIS sebagai metode pembayaran.',
            'jenis_konten' => 'Video',
            'konten_teks' => '<p>Perhatikan nama merchant saat memindai kode QRIS. Pastikan sesuai dengan nama toko...</p>',
            'link_youtube' => [],
            'link_drive' => [],
            'images' => []
        ]);

        MateriEdukasi::create([
            'kategori_materi_id' => $kategoriId,
            'judul' => 'E-Book: Literasi Keuangan Digital 2026',
            'slug' => Str::slug('E-Book: Literasi Keuangan Digital 2026') . '-' . uniqid(),
            'deskripsi_singkat' => 'Buku panduan lengkap tentang tren literasi keuangan digital untuk masyarakat.',
            'jenis_konten' => 'E-Book',
            'konten_teks' => '<p>Panduan ini mencakup penjelasan mendalam terkait keuangan digital, mulai dari perbankan hingga fintech.</p>',
            'link_youtube' => [],
            'link_drive' => [],
            'images' => []
        ]);
    }
}
