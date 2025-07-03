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
        // Add `is_flagged` column to `quiz_question` table
        Schema::table('quiz_question', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('time_spent');
        });

        // Add `timer` column to `quizzes` table (total seconds)
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('timer')->unsigned()->nullable()->after('status');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop `is_flagged` column from `quiz_question` table
        Schema::table('quiz_question', function (Blueprint $table) {
            $table->dropColumn('is_flagged');
        });

        // Drop `timer` column from `quizzes` table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('timer');
        });
    }
};
