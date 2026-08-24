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
        if (!Schema::hasTable('footer_settings')) {
            Schema::create('footer_settings', function (Blueprint $table) {
                $table->id();
                $table->text('deskripsi')->nullable();
                $table->text('alamat')->nullable();
                $table->string('telepon')->nullable();
                $table->string('email')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('youtube_url')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('tiktok_url')->nullable();
                $table->string('copyright_text')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
