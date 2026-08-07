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
            $table->string('province')->nullable()->after('address');
            $table->index('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edukasi_locations', function (Blueprint $table) {
            $table->dropIndex(['province']);
            $table->dropColumn('province');
        });
    }
};
