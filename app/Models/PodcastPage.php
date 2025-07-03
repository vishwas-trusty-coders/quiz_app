<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 
        'section1_title', 'section1_subtitle', 'section1_description', 'section1_image', 'podcast_details', 'section_title', 'guest_details',
    ];

    protected $casts = [
        'podcast_details' => 'array',
        'guest_details' => 'array',
    ];
}
