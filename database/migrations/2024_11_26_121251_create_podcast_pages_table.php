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
        Schema::create('podcast_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('section1_title')->nullable();
            $table->string('section1_subtitle')->nullable();
            $table->text('section1_description')->nullable();
            $table->text('section1_image')->nullable();
            $table->json('podcast_details')->nullable();
            $table->text('section_title')->nullable();
            $table->json('guest_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_pages');
    }
};
