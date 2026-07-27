<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('kategori'); // sosialisasi, seminar, workshop, pameran, dll
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_kegiatan');
            $table->string('posted_by')->default('Admin BI');
            $table->json('images')->nullable();       // array path foto
            $table->json('video_urls')->nullable();   // array URL YouTube/GDrive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
