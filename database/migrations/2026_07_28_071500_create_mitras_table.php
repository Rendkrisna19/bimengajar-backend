<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('singkatan');
            $table->string('nama_lengkap');
            $table->string('kategori');
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->string('no_wa');
            $table->string('status_persetujuan')->default('menunggu'); // menunggu, diterima, ditolak
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
