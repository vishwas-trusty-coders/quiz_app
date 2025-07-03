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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question_label')->nullable()->after('id'); // Replace 'column_name' with the actual column name
            $table->dropColumn(['question_image', 'explanation_image']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question_image')->nullable();
            $table->string('explanation_image')->nullable();
            $table->dropColumn('question_label');
        });
    }
};
