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
        Schema::create('edukasi_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['SD', 'SMP', 'SMA/SMK', 'Perguruan Tinggi', 'Komunitas']);
            $table->year('year')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->json('activities')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edukasi_locations');
    }
};
