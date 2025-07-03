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
        Schema::create('homepages', function (Blueprint $table) {
            $table->id();
            // Hero Section
            $table->string('hero_title');
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_bg_image')->nullable();
            $table->string('cta_text1')->nullable();
            $table->string('cta_link1')->nullable();
            $table->string('cta_text2')->nullable();
            $table->string('cta_link2')->nullable();
            
            // Hero Statistics
            $table->json('statistics')->nullable();

            // About Section
            $table->string('about_title');
            $table->text('about_description')->nullable();
            $table->json('mission_and_values')->nullable();
            $table->json('founder_data')->nullable();
            $table->json('educational_approach')->nullable();
            $table->string('about_us_button_text')->nullable();
            $table->string('about_us_button_url')->nullable();
            $table->string('about_image')->nullable();
            $table->string('about_video')->nullable();

            // Why Choose Us Section
            $table->string('why_title');
            $table->string('why_subtitle')->nullable();
            $table->json('why_choose_us_data')->nullable();
            $table->string('why_choose_us_video')->nullable();

            // Testimonial Section
            $table->string('testimonial_title');
            $table->string('testimonial_subtitle')->nullable();
            $table->json('testimonial_data')->nullable();

            // Tutor Expert Section
            $table->string('tutor_title');
            $table->string('tutor_subtitle')->nullable();
            $table->text('tutor_description')->nullable();
            $table->string('tutor_button_text')->nullable();
            $table->string('tutor_button_link')->nullable();
            $table->json('tutor_image')->nullable();

            // Podcast Section
            $table->string('podcast_title');
            $table->string('podcast_subtitle')->nullable();
            $table->text('podcast_description')->nullable();
            $table->string('podcast_button_text')->nullable();
            $table->string('podcast_button_link')->nullable();
            $table->json('podcast_image')->nullable();

            // FAQ Section
            $table->string('faq_title');
            $table->string('faq_subtitle')->nullable();
            $table->string('faq_image')->nullable();
            $table->json('faq_list')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepages');
    }
};
