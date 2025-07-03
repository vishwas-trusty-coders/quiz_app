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
        // Step 1: Drop the existing column
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('question_status');
        });

        // Step 2: Add the column back as a JSON type
        Schema::table('quizzes', function (Blueprint $table) {
            $table->json('question_status')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Revert the column to enum type with a default value
            $table->enum('question_status', ['all', 'new', 'incorrect', 'correct', 'flagged'])
                ->default('all')
                ->change();
        });
    }
};
