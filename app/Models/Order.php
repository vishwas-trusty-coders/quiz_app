<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paypal_order_id',
        'amount',
        'currency',
        'status',
        'credits', // Include credits
        'response_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
