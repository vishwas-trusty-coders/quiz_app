<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory;

    protected $fillable = [
        // Hero Section
        'hero_title', 'hero_subtitle', 'hero_description', 'hero_image', 'hero_bg_image',
        'cta_text1', 'cta_link1', 'cta_text2', 'cta_link2',

        // Hero Statistics
        'statistics',

        // About Section
        'about_title', 'about_description', 'mission_and_values', 'founder_data', 
        'educational_approach', 'about_us_button_text', 'about_us_button_url',
        'about_image', 'about_video',

        // Why Choose Us Section
        'why_title', 'why_subtitle', 'why_choose_us_data', 'why_choose_us_video',

        // Testimonial Section
        'testimonial_title', 'testimonial_subtitle', 'testimonial_data',

        // Tutor Expert Section
        'tutor_title', 'tutor_subtitle', 'tutor_description', 'tutor_button_text',
        'tutor_button_link', 'tutor_image',

        // Podcast Section
        'podcast_title', 'podcast_subtitle', 'podcast_description', 'podcast_button_text',
        'podcast_button_link', 'podcast_image',

        // FAQ Section
        'faq_title', 'faq_subtitle', 'faq_image', 'faq_list',
    ];

    protected $casts = [
        'statistics' => 'array',
        'mission_and_values' => 'array',
        'founder_data' => 'array',
        'educational_approach' => 'array',
        'why_choose_us_data' => 'array',
        'testimonial_data' => 'array',
        'tutor_image' => 'array',
        'podcast_image' => 'array',
        'faq_list' => 'array',
    ];
}
