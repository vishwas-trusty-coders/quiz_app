<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        // Add other fields as needed
    ];

    // Define relationships if necessary
    public function users()
    {
        return $this->belongsToMany(User::class); // Example relationship
    }
}
