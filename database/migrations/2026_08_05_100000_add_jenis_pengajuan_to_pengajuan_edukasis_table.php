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
            if (!Schema::hasColumn('pengajuan_edukasis', 'jenis_pengajuan')) {
                $table->enum('jenis_pengajuan', ['mengunjungi', 'dikunjungi'])->default('mengunjungi')->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_edukasis', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_edukasis', 'jenis_pengajuan')) {
                $table->dropColumn('jenis_pengajuan');
            }
        });
    }
};
