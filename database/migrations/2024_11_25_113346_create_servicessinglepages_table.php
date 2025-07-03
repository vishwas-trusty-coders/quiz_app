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
        Schema::create('services_single_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('section1_description')->nullable();
            $table->string('section1_title')->nullable();
            $table->json('quick_facts')->nullable();
            $table->text('section1_cta_button_txt')->nullable();
            $table->text('section1_cta_button_url')->nullable();
            $table->string('section1_video')->nullable();
            $table->json('section2_content')->nullable();
            $table->string('section3_title')->nullable();
            $table->json('consultant_services')->nullable();
            $table->string('section4_title')->nullable();
            $table->text('section4_description')->nullable();
            $table->json('available_plans')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_single_pages');
    }
};
