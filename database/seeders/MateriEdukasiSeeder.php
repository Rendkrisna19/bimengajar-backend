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
            'konten_teks' => '<h2>Apa itu Sistem Pembayaran?</h2><p>Sistem pembayaran adalah sistem yang mencakup seperangkat aturan, lembaga, dan mekanisme yang dipakai untuk melaksanakan pemindahan dana guna memenuhi suatu kewajiban yang timbul dari suatu kegiatan ekonomi.</p><blockquote>Sistem pembayaran berperan sebagai urat nadi perekonomian suatu negara. Kegagalan sistem pembayaran dapat mengganggu stabilitas keuangan dan ekonomi secara makro.</blockquote><h3>Komponen Utama Sistem Pembayaran</h3><p>Dalam ekosistem sistem pembayaran di Indonesia, terdapat beberapa komponen penting:</p><ul><li><strong>Alat Pembayaran:</strong> Berupa alat pembayaran tunai (uang kartal) dan non-tunai (kartu debit, kredit, uang elektronik, QRIS).</li><li><strong>Infrastruktur:</strong> Jaringan transmisi data, kliring, dan setelmen.</li><li><strong>Lembaga Penyelenggara:</strong> Bank Indonesia selaku regulator, bank umum, lembaga kliring, dan penyedia jasa pembayaran (PJP).</li></ul><p>Pelajari lebih lanjut mengenai regulasi terbaru melalui website resmi <a href="https://www.bi.go.id" target="_blank">Bank Indonesia</a>.</p>',
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
            'konten_teks' => '<h2>Panduan Transaksi Aman QRIS</h2><p>QRIS (Quick Response Code Indonesian Standard) memudahkan transaksi non-tunai di seluruh Indonesia. Namun, pengguna tetap harus waspada demi menghindari penipuan digital.</p><h3>Tips Keamanan QRIS bagi Pengguna:</h3><ol><li><strong>Periksa Nama Merchant:</strong> Selalu pastikan nama toko/merchant yang muncul di layar aplikasi Anda sebelum menekan tombol bayar atau memasukkan PIN.</li><li><strong>Periksa Fisik Kode QR:</strong> Pastikan stiker QRIS di toko tidak ditumpuk atau ditempeli stiker lain.</li><li><strong>Jangan Bagikan PIN/OTP:</strong> PIN dompet digital Anda bersifat rahasia dan jangan pernah diberikan kepada siapapun.</li></ol><blockquote>Selalu pastikan nominal yang tertera pada layar konfirmasi sesuai dengan harga barang yang dibeli sebelum memasukkan PIN.</blockquote>',
            'link_youtube' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            'link_drive' => [],
            'images' => []
        ]);

        MateriEdukasi::create([
            'kategori_materi_id' => $kategoriId,
            'judul' => 'E-Book: Literasi Keuangan Digital 2026',
            'slug' => Str::slug('E-Book: Literasi Keuangan Digital 2026') . '-' . uniqid(),
            'deskripsi_singkat' => 'Buku panduan lengkap tentang tren literasi keuangan digital untuk masyarakat.',
            'jenis_konten' => 'E-Book',
            'konten_teks' => '<h2>E-Book Literasi Keuangan Digital</h2><p>Buku panduan ini disusun sebagai langkah nyata untuk meningkatkan pemahaman masyarakat Indonesia terkait produk dan layanan keuangan berbasis teknologi digital.</p><h3>Mengapa Literasi Keuangan Digital itu Penting?</h3><p>Perkembangan teknologi finansial (Fintech) yang pesat menuntut masyarakat untuk adaptif sekaligus cerdas mengelola keuangan secara digital. Beberapa manfaat utamanya antara lain:</p><ul><li>Meminimalkan risiko terjerat investasi bodong atau pinjaman online ilegal.</li><li>Mengoptimalkan penggunaan fitur perbankan digital untuk menabung dan berinvestasi.</li><li>Memahami perlindungan data pribadi dalam transaksi keuangan.</li></ul><p>Gunakan link unduhan di bawah untuk mendapatkan dokumen lengkap.</p>',
            'link_youtube' => [],
            'link_drive' => [],
            'images' => []
        ]);
    }
}
