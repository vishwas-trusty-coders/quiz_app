<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'credit', 'subjects', 'topics', 'tags'];

    protected $casts = [
        'subjects' => 'array', 
        'topics' => 'array', 
        'tags' => 'array', 
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'question_bank_id');
    }
}
