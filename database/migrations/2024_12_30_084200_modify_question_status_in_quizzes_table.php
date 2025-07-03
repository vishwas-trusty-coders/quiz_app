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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('question_status', ['all', 'new', 'incorrect', 'correct', 'flagged'])
            ->default('all')
            ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('question_status', ['all', 'new', 'unused', 'incorrect', 'correct'])
            ->default('all')
            ->change();
        });
    }
};
