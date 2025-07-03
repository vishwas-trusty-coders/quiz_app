<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    const ROLE_USER = 'user';
    const ROLE_OWNER = 'owner';
    const ROLE_ADMINISTRATOR = 'administrator';
    use HasFactory;

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'name', // Add this line
    ];
}
