<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\packagePurchase as Purchase;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Order;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch purchases for the authenticated user
        $purchases = Purchase::with(['user', 'questionBank'])
        ->where('user_id', auth()->id()) 
        ->latest()
        ->take(5)
        ->get();
        
        foreach ($purchases as $purchase) {
            if (!empty($purchase->questionBank)) {
                // Fetch subjects with question count
                $subjects = Subject::whereIn('id', $purchase->questionBank->subjects)
                ->withCount(['questions']) // Assuming a questions relationship exists in Subject
                ->get();
                
                $subjectNames = $subjects->map(function ($subject) {
                    return $subject->name . ' (' . $subject->questions_count . ')';
                })->toArray();
                if(!empty($subjectNames)){
// Fetch topics with question count
                $topics = Topic::whereIn('id', $purchase->questionBank->topics)
                ->withCount(['questions']) // Assuming a questions relationship exists in Topic
                ->get();
                
                $topicNames = $topics->map(function ($topic) {
                    return $topic->name . ' (' . $topic->questions_count . ')';
                })->toArray();
                
                // Attach the names with counts
                $purchase->subject_names = implode(', ', $subjectNames);
                $purchase->topic_names = implode(', ', $topicNames);
                }
                
            } else {
                // Fallback values
                $purchase->subject_names = 'N/A';
                $purchase->topic_names = 'N/A';
            }
        }
        
        $orders = Order::with(['user'])
                ->where('user_id', auth()->id()) // Fetch only current user's purchases
                ->latest()
                ->take(5)
                ->get();

        // Fetch quizzes for the logged-in user
        $quizzes = Quiz::with(['questions' => function ($query) {
            $query->select('quiz_id', 'is_attempted', 'is_correct'); // Fetch only relevant columns
        }])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

       

        // Process quiz data for display
        $quizzesData = $quizzes->map(function ($quiz) {

            $subjectCounts = $quiz->getQuestionCountBySubjects();
            $subjects = $quiz->subjects()->map(function ($subject) use ($subjectCounts) {
                $questionCount = $subjectCounts->get($subject->id)?->question_count ?? 0;
                return [
                    'name' => $subject->name,
                    'question_count' => $questionCount,
                ];
            });
            $totalQuestions = $quiz->question_quantity;
            // Fetch topics and their question counts
            $topicCounts = $quiz->getQuestionCountByTopics();
            $topics = $quiz->topics()->map(function ($topic) use ($topicCounts) {
                $questionCount = $topicCounts->get($topic->id)?->question_count ?? 0;
                return [
                    'name' => $topic->name,
                    'question_count' => $questionCount,
                ];
            });

            $attempted = $quiz->questions->where('is_attempted', true)->count();
            $correct = $quiz->questions->where('is_correct', true)->count();
            $mode = '';
            if($quiz->tutor_mode == 'on'){
                $mode = 'tutor';
            }else{
                $mode = 'timed';
            }

           return [
                'id' => $quiz->id,
                'name' => $quiz->name,
                'subjects' =>  $subjects,
                'topics' => $topics,
                'status' => $quiz->status,
                'score' => $quiz->score,
                'progress' => $totalQuestions > 0 ? round(($attempted / $totalQuestions) * 100) : 0,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correct,
                'attempted_questions' => $attempted,
                'updated_at' => date('F d, Y', strtotime($quiz->updated_at)),
                'mode' => $mode
            ];
        });

        // Pass the data to the dashboard view
        return view('dashboard', compact('purchases', 'orders', 'quizzesData'));
    }

    private function getQuizStatus($score, $attempted)
    {
        if ($score == 100) {
            return 'Completed';
        } elseif ($attempted > 0) {
            return 'In Progress';
        } else {
            return 'Not Started';
        }
    }
}
