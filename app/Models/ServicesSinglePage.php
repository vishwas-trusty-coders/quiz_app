<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesSinglePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 
        'section1_description', 'section1_title', 'quick_facts', 'section1_cta_button_txt', 'section1_cta_button_url', 'section1_video',
        'section2_content',
        'section3_title', 'consultant_services',
        'section4_title', 'section4_description', 'available_plans'
    ];

    protected $casts = [
        'quick_facts' => 'array',
        'section2_content' => 'array',
        'consultant_services' => 'array',
        'available_plans' => 'array'
    ];
}
