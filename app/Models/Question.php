<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // 'question_label',
        'question',
        'subject_id',
        'topic_id',
        'options',
        'correct_answer',
        'explanation',
        'tag_ids'
    ];

    protected $casts = [
        'options' => 'array',
        'tag_ids' => 'array',
    ];

    /**
     * Relationship with Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship with Topic.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function quiz()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions', 'question_id', 'quiz_id') ->withPivot('is_attempted', 'is_correct', 'selected_answer')->withTimestamps();
    }

    public function getTagNamesAttribute()
    {
        // Fetch tag names based on the ids in the tag_ids array
        return Tag::whereIn('id', $this->tag_ids ?? [])->pluck('name')->toArray();
    }
}
