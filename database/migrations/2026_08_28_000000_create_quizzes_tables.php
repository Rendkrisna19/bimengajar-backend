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
        if (!Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('category')->default('Kebanksentralan');
                $table->text('description')->nullable();
                $table->string('difficulty')->default('Sedang');
                $table->string('mode')->default('both'); // solo, multiplayer, both
                $table->integer('estimated_time_minutes')->default(5);
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
                $table->text('question_text');
                $table->json('options');
                $table->integer('time_limit_seconds')->default(15);
                $table->text('explanation')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quiz_results')) {
            Schema::create('quiz_results', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quiz_id')->nullable();
                $table->string('quiz_title');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('nickname');
                $table->string('avatar')->nullable();
                $table->string('mode')->default('solo'); // solo, multiplayer
                $table->integer('score')->default(0);
                $table->integer('correct_answers')->default(0);
                $table->integer('total_questions')->default(0);
                $table->string('pin_code')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quiz_sessions')) {
            Schema::create('quiz_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quiz_id');
                $table->string('pin_code')->unique();
                $table->string('status')->default('waiting'); // waiting, playing, finished
                $table->integer('current_question_index')->default(0);
                $table->json('participants')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_sessions');
        Schema::dropIfExists('quiz_results');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
