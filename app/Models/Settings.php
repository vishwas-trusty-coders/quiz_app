<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_email',
        'support_email',
        'currency',
        'social_media_links',
        'top_header_text',
        'credit_info',
        'paypal_sandbox_client_id',
        'paypal_sandbox_client_secret',
        'paypal_production_client_id',
        'paypal_production_client_secret',
        'paypal_environment',
    ];

    protected $casts = [
        'social_media_links' => 'array',
        'credit_info' => 'array',
    ];
    
}
