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
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('subtitle_en')->nullable()->after('subtitle');
            $table->string('button_primary_text_en')->nullable()->after('button_primary_url');
            $table->string('button_secondary_text_en')->nullable()->after('button_secondary_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->dropColumn([
                'title_en',
                'subtitle_en',
                'button_primary_text_en',
                'button_secondary_text_en'
            ]);
        });
    }
};
