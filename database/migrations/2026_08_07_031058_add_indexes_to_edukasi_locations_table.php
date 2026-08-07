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
        Schema::table('edukasi_locations', function (Blueprint $table) {
            $table->index('name');
            $table->index('category');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edukasi_locations', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category']);
            $table->dropIndex(['year']);
        });
    }
};
