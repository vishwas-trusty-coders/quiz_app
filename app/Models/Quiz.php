<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Quiz extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'question_bank_id', 'tutor_mode', 'timed_mode', 'subject_ids', 'topic_ids', 'question_status', 'question_quantity', 'timer', 'remaining_time', 'total_time'
    ];

    protected $casts = [
        'subject_ids' => 'array',
        'topic_ids' => 'array',
        'question_status' => 'array'
    ];

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_question', 'quiz_id', 'question_id')
                    ->withPivot('id', 'is_attempted', 'is_correct', 'selected_answer', 'is_flagged', 'time_spent') // Include any additional pivot columns you need
                    ->withTimestamps();
    }

    public function subjects()
    {
        $subjectIds = json_decode($this->subject_ids, true);
        return Subject::whereIn('id', $subjectIds)->get();
    }

    // Topics relationship
    public function topics()
    {
        $topicIds = json_decode($this->topic_ids, true);
        return Topic::whereIn('id', $topicIds)->get();
    }

    public function getQuestionCountBySubjects()
    {
        $subjectIds = json_decode($this->subject_ids, true); // Decode JSON to array
    
        // Fetch question counts grouped by subject ID
        return Question::whereIn('subject_id', $subjectIds)
            ->select('subject_id', \DB::raw('COUNT(*) as question_count'))
            ->groupBy('subject_id')
            ->get()
            ->keyBy('subject_id');
    }

    public function getQuestionCountByTopics()
    {
        $topicIds = json_decode($this->topic_ids, true); // Decode JSON to array

        // Fetch question counts grouped by topic ID
        return Question::whereIn('topic_id', $topicIds)
            ->select('topic_id', \DB::raw('COUNT(*) as question_count'))
            ->groupBy('topic_id')
            ->get()
            ->keyBy('topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
