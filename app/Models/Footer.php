<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'logo',
        'tagline',
        'description',
        'email',
        'links',        // JSON field for footer links
        'social_links', // JSON field for social links
        'copyright',
        'privacy_policy',
        'terms_of_use',
        'custom_links',
    ];

    // Ensure JSON fields are cast properly
    protected $casts = [
        'links' => 'array',
        'social_links' => 'array',
        'custom_links' => 'array',
    ];
}
