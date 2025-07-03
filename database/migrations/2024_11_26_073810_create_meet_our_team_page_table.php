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
        Schema::create('meet_our_team_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('section1_title')->nullable();
            $table->text('leaders1_image')->nullable();
            $table->string('leaders1_name')->nullable();
            $table->text('leaders1_designation')->nullable();
            $table->text('leaders1_description')->nullable();
            $table->string('credential_title')->nullable();
            $table->json('credential_info')->nullable();
            $table->string('exam_score_title')->nullable();
            $table->json('exam_score_info')->nullable();
            $table->text('leaders2_image')->nullable();
            $table->string('leaders2_name')->nullable();
            $table->text('leaders2_designation')->nullable();
            $table->text('leaders2_description')->nullable();
            $table->string('section2_title')->nullable();
            $table->text('section2_desc')->nullable();
            $table->json('tutor_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meet_our_team_pages');
    }
};
