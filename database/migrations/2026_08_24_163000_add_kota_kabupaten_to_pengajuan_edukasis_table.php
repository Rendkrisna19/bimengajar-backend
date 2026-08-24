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
        Schema::table('pengajuan_edukasis', function (Blueprint $table) {
            $table->string('kota_kabupaten')->nullable()->after('lokasi_kegiatan');
            $table->string('durasi')->nullable()->after('waktu_selesai');
            $table->string('tujuan_kegiatan')->nullable()->after('tema_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_edukasis', function (Blueprint $table) {
            $table->dropColumn(['kota_kabupaten', 'durasi', 'tujuan_kegiatan']);
        });
    }
};
