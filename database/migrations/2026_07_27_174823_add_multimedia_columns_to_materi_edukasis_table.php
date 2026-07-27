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
        Schema::table('materi_edukasis', function (Blueprint $table) {
            $table->json('images')->nullable()->after('thumbnail');
            $table->json('link_youtube')->nullable()->after('link_eksternal');
            $table->json('link_drive')->nullable()->after('link_youtube');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi_edukasis', function (Blueprint $table) {
            $table->dropColumn(['images', 'link_youtube', 'link_drive']);
        });
    }
};
