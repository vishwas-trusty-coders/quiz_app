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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedBigInteger('question_bank_id');
            $table->foreign('question_bank_id')->references('id')->on('question_banks')->onDelete('cascade');
            $table->enum('tutor_mode', ['on', 'off'])->default('off');
            $table->enum('timed_mode', ['on', 'off'])->default('off');
            $table->json('subject_ids'); 
            $table->json('topic_ids');   
            $table->enum('question_status', ['all', 'new', 'unused', 'incorrect', 'correct'])->default('all');
            $table->integer('question_quantity')->default(0);
            $table->integer('score')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quiz_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('question_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->string('status')->default('new');
            $table->boolean('is_attempted')->default(false);
            $table->boolean('is_correct')->nullable(); 
            $table->string('selected_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_question');
        Schema::dropIfExists('quizzes');
    }
};
