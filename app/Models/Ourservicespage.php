<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ourservicespage extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'title', 'description', 
        'service1_title', 'service1_description', 'service1_title2', 'service1_how_it_work', 'cta_button_service1', 'service1_image', 'service1_more_description',
        'service2_title', 'service2_description', 'service2_title2', 'service2_how_it_work', 'cta_button_service2', 'service2_image', 'service2_more_description',
    ];

    protected $casts = [
        'service1_how_it_work' => 'array',
        'cta_button_service1' => 'array',
        'service2_how_it_work' => 'array',
        'cta_button_service2' => 'array'
    ];
}
