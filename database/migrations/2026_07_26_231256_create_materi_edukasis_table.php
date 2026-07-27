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
        Schema::create('materi_edukasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_materi_id')->constrained('kategori_materis')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi_singkat')->nullable();
            $table->enum('jenis_konten', ['Artikel', 'Infografis', 'Video', 'E-Book', 'Presentasi']);
            $table->string('thumbnail')->nullable();
            $table->string('file_path')->nullable();
            $table->string('link_eksternal')->nullable();
            $table->longText('konten_teks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_edukasis');
    }
};
