<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_edukasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->enum('jenis_pengajuan', ['mengunjungi', 'dikunjungi'])->default('mengunjungi');
            
            // Data Instansi
            $table->string('jenis_instansi');
            $table->string('nama_instansi');
            $table->text('alamat_instansi');
            
            // Data PIC
            $table->string('nama_pic');
            $table->string('jabatan_pic');
            $table->string('email_pic');
            $table->string('no_telp_pic');
            
            // Data Kegiatan
            $table->string('tema_kegiatan')->nullable();
            $table->text('deskripsi_kegiatan')->nullable();
            $table->integer('jumlah_peserta')->nullable();
            $table->date('tanggal_kegiatan')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi_kegiatan')->nullable();
            
            // Dokumen
            $table->string('dokumen_proposal')->nullable();
            
            // Status & Meta
            $table->enum('status', ['pending', 'verifikasi', 'penjadwalan', 'konfirmasi', 'ditolak', 'selesai'])->default('pending');
            $table->text('catatan_admin')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_edukasis');
    }
};
