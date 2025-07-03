<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'menu_items',
    ];

    protected $casts = [
        'menu_items' => 'array', // Ensure menu items are cast to an array
    ];
}
