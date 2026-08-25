<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pre_post_tests')) {
            Schema::create('pre_post_tests', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->enum('tipe', ['pre-test', 'post-test'])->default('pre-test');
                $table->text('deskripsi')->nullable();
                $table->json('slides')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('test_submissions')) {
            Schema::create('test_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('test_id')->nullable();
                $table->string('nama_peserta');
                $table->string('instansi')->nullable();
                $table->string('email')->nullable();
                $table->integer('skor_total')->default(0);
                $table->integer('skor_maksimal')->default(100);
                $table->json('detail_jawaban')->nullable();
                $table->timestamp('waktu_selesai')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('test_submissions');
        Schema::dropIfExists('pre_post_tests');
    }
};
