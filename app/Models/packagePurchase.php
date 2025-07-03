<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class packagePurchase extends Model
{
    protected $fillable = ['user_id', 'question_bank_id', 'credits_spent', 'subject', 'topic', 'question_bank_name'];
    
    protected $casts = [
        'subject' => 'array', 
        'topic' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, 'subjects', 'id', 'id', 'subject', 'id');
    }

    public function topics()
    {
        return $this->hasManyThrough(Topic::class, 'topicss', 'id', 'id', 'topic', 'id');
    }
}
