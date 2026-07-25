<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('user_type', ['perorangan', 'umkm', 'instansi'])->default('perorangan');
            $table->string('whatsapp', 20);
            $table->text('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->bigInteger('total_coins')->default(0); // in Rupiah
            $table->json('denominations'); // e.g. ["100","200","500","1000"]
            $table->string('operational_hours', 100)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_providers');
    }
};
