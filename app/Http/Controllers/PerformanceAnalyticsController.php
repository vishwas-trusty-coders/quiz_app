<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceAnalyticsController extends Controller
{
    public function getQuizData(){
        $userId = auth()->id();

        $quizzes = DB::table('quizzes')
            ->join('quiz_question', 'quizzes.id', '=', 'quiz_question.quiz_id') // Join quizzes with pivot table
            ->join('questions', 'quiz_question.question_id', '=', 'questions.id') // Join questions
            ->join('topics', 'questions.topic_id', '=', 'topics.id') // Join topics
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id') // Join subjects
            ->select(
                'quizzes.id as quiz_id',
                'quizzes.name as quiz_name',
                'topics.id as topic_id',
                'topics.name as topic_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                DB::raw('COUNT(questions.id) as total_questions'),
                DB::raw('SUM(CASE WHEN quiz_question.is_correct = 1 THEN 1 ELSE 0 END) as correct_questions'),
                DB::raw('SUM(CASE WHEN quiz_question.is_correct = 0 AND quiz_question.is_attempted = 1 THEN 1 ELSE 0 END) as incorrect_questions'),
                DB::raw('SUM(CASE WHEN quiz_question.is_attempted = 0 THEN 1 ELSE 0 END) as not_attempted')
            )
            ->where('quizzes.user_id', $userId) // Filter by user
            ->where('quizzes.status', 'completed') // Filter by status
            ->groupBy(
                'quizzes.id',
                'quizzes.name',
                'topics.id',
                'topics.name',
                'subjects.id',
                'subjects.name'
            ) // Group by quiz, topic, and subject
            ->orderBy('quizzes.created_at', 'desc')
            ->get();

        $quizzesData = [];

        foreach ($quizzes as $quiz) {
            // Organize subjects and topics under each quiz
            if (!isset($quizzesData[$quiz->quiz_id])) {
                $quizzesData[$quiz->quiz_id] = [
                    'quiz_name' => $quiz->quiz_name,
                    'total_questions' => 0,
                    'correct_answers' => 0,
                    'incorrect_answers' => 0,
                    'not_attempted' => 0,
                    'subjects' => [],
                ];
            }

            // Aggregate total quiz data
            $quizzesData[$quiz->quiz_id]['total_questions'] += $quiz->total_questions;
            $quizzesData[$quiz->quiz_id]['correct_answers'] += $quiz->correct_questions;
            $quizzesData[$quiz->quiz_id]['incorrect_answers'] += $quiz->incorrect_questions;
            $quizzesData[$quiz->quiz_id]['not_attempted'] += $quiz->not_attempted;

            // Organize subject data
            if (!isset($quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id])) {
                $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id] = [
                    'subject_name' => $quiz->subject_name,
                    'total_questions' => 0,
                    'correct_answers' => 0,
                    'incorrect_answers' => 0,
                    'not_attempted' => 0,
                    'topics' => [],
                ];
            }

            // Aggregate subject data
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['total_questions'] += $quiz->total_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['correct_answers'] += $quiz->correct_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['incorrect_answers'] += $quiz->incorrect_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['not_attempted'] += $quiz->not_attempted;

            // Organize topic data within each subject
            if (!isset($quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id])) {
                $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id] = [
                    'topic_name' => $quiz->topic_name,
                    'total_questions' => 0,
                    'correct_answers' => 0,
                    'incorrect_answers' => 0,
                    'not_attempted' => 0,
                ];
            }

            // Aggregate topic data
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id]['total_questions'] += $quiz->total_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id]['correct_answers'] += $quiz->correct_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id]['incorrect_answers'] += $quiz->incorrect_questions;
            $quizzesData[$quiz->quiz_id]['subjects'][$quiz->subject_id]['topics'][$quiz->topic_id]['not_attempted'] += $quiz->not_attempted;
        }

        // question bank stat data start
        $questionBanks = DB::table('question_banks')
            ->join('package_purchases', function ($join) use ($userId) {
                $join->on('question_banks.id', '=', 'package_purchases.question_bank_id')
                    ->where('package_purchases.user_id', '=', $userId);
            })
            ->select(
                'question_banks.id as question_bank_id',
                'question_banks.name as question_bank_name',
                'question_banks.subjects',
                DB::raw('IF(package_purchases.user_id IS NOT NULL, 1, 0) as is_purchased') // 1 if purchased, 0 if not
            )
            ->get();
        
        $subjectQuestion = [];
        
        foreach ($questionBanks as $questionBanksdata) {
            $subjects = json_decode($questionBanksdata->subjects);
        
            // Get all questions related to the subjects in one query
            $questions = DB::table('questions')
                ->select('id as question_id', 'subject_id')
                ->whereIn('subject_id', $subjects)
                ->get();
        
            // Group the questions by question_bank_id
            foreach ($questions as $question) {
                $subjectQuestion[$questionBanksdata->question_bank_id][] = $question;
            }
        }
        
        $quizQuestionsdata = [];
        foreach ($subjectQuestion as $questionBankId => $questions) {
            // Query quiz data for all questions at once using `whereIn`
            // Query quiz data for all questions at once using `whereIn`
                $quizData = DB::table('quizzes')
                ->leftJoin('quiz_question', 'quizzes.id', '=', 'quiz_question.quiz_id')
                ->select('quiz_question.question_id', 'quiz_question.is_correct', 'quiz_question.is_attempted')
                ->whereIn('quiz_question.question_id', array_column($questions, 'question_id'))
                ->where('quizzes.question_bank_id', $questionBankId)
                ->where('quizzes.status', 'completed')
                ->get();

            // Group the quiz data by question_id
            $groupedQuizData = $quizData->groupBy('question_id');

            // Initialize each question's data with default values
            foreach ($questions as $question) {
                $questionId = $question->question_id;

                // If no quiz data exists for this question, set default values
                if (!isset($groupedQuizData[$questionId])) {
                    $groupedQuizData[$questionId] = collect([
                        (object)[
                            'is_correct' => 0,
                            'is_attempted' => 0
                        ]
                    ]);
                }
            }

            // Store the grouped quiz data for each question_bank_id
            $quizQuestionsdata[$questionBankId] = $groupedQuizData;
        }
        $results = [];
        $questionBankStats = [];
        foreach ($quizQuestionsdata as $questionBankId => $dataquiz) {
            $total_questions = 0;
            $correct_answers = 0;
            $incorrect_answers = 0;
            $not_completed = 0;
            foreach ($dataquiz as $questionId => $answers) {
                $is_correct = 0;  
                $is_attempted = 0; 
                foreach ($answers as $answer) {
                    if ($answer->is_correct == 1) {
                        $is_correct = 1;
                    }
                    if ($answer->is_attempted == 1) {
                        $is_attempted = 1;
                    }
                }

                // Count total questions
                $total_questions++;

                // Count based on the conditions
                if ($is_correct == 1 && $is_attempted == 1) {
                    $correct_answers++;
                } elseif ($is_correct == 0 && $is_attempted == 1) {
                    $incorrect_answers++;
                } elseif ($is_correct == 0 && $is_attempted == 0) {
                    $not_completed++;
                }

                // Store the results for each question
                $results[$questionBankId][$questionId] = [
                    'is_correct' => $is_correct,
                    'is_attempted' => $is_attempted
                ];
            }
            $questionBankDetails = $questionBanks->firstWhere('question_bank_id', $questionBankId);
            $questionBankStats[$questionBankId] = [
                'question_bank_link' => route('performance.question_bank', ['questionBankId' => $questionBankId]), 
                'question_bank_name' => $questionBankDetails->question_bank_name,
                'total_questions' => $total_questions,
                'correct_answers' => $correct_answers,
                'incorrect_answers' => $incorrect_answers,
                'not_completed' => $not_completed
            ];
        }
        // question bank stat data end
       
        return view('performance_analytics', compact('quizzesData', 'questionBankStats'));
    }

    //get analytics of all subjects of selected question bank
    function getAnalyticsByQuestionBank($questionBankId){

        $subjectStats = [];
        // Fetch question bank details by ID
        $questionBank = DB::table('question_banks')
            ->where('id', $questionBankId)
            ->select('id as question_bank_id', 'name as question_bank_name', 'subjects')
            ->first();

        if ($questionBank) {
            $subjects = json_decode($questionBank->subjects);
            
            foreach ($subjects as $subjectId) {
                // Fetch all questions for this subject
                $questions = DB::table('questions')
                    ->select('id as question_id', 'subject_id')
                    ->where('subject_id', $subjectId)
                    ->get();

                // Fetch quiz data for the questions in this subject
                $quizData = DB::table('quizzes')
                    ->leftJoin('quiz_question', 'quizzes.id', '=', 'quiz_question.quiz_id')
                    ->select('quiz_question.question_id', 'quiz_question.is_correct', 'quiz_question.is_attempted', 'quiz_question.selected_answer')
                    ->whereIn('quiz_question.question_id', $questions->pluck('question_id')->toArray())
                    ->where('quizzes.status', 'completed')
                    ->get()
                    ->groupBy('question_id');

                // Initialize counters
                $total_questions = count($questions);
                $correct_answers = 0;
                $incorrect_answers = 0;
                $omitted = 0;
                $usage = 0;
                
                foreach ($questions as $question) {
                    $questionId = $question->question_id;
                    $is_correct = 0;
                    $is_attempted = 0;
                    $not_attempted = 0;
                    
                    if (isset($quizData[$questionId])) {
                        foreach ($quizData[$questionId] as $quizEntry) {
                            if ($quizEntry->is_correct == 1) {
                                $is_correct = 1;
                            }
                            if ($quizEntry->is_attempted == 1) {
                                $is_attempted = 1;
                            }
                            if ($quizEntry->selected_answer == null && $quizEntry->is_attempted == 0) {
                                $not_attempted = 1;
                            }
                        }
                    }
                    
                    if($is_attempted == 1){
                        $usage++;
                    }
                    if ($is_correct == 1 && $is_attempted == 1) {
                        $correct_answers++;
                    } elseif ($is_correct == 0 && $is_attempted == 1) {
                        $incorrect_answers++;
                    } elseif ($not_attempted == 1 && $is_attempted == 0) {
                        $omitted++;
                    }
                }
                $subjectDetails = Subject::find($subjectId);
                $subjectStats[$subjectId] = [
                    'subject_link' => route('performance.subject', ['subjectId' => $subjectId]), 
                    'subject_id' => $subjectId,
                    'subject_name' => $subjectDetails->name,
                    'usage'=>$usage,
                    'total_questions' => $total_questions,
                    'correct_answers' => $correct_answers,
                    'incorrect_answers' => $incorrect_answers,
                    'not_attempted' => $omitted,
                ];
            }
        }
        return view('analytics_by_questionbank', compact('subjectStats'));
    }

    // Get analytics of all topics of selected subjects
    function getAnalyticsBySubject($subjectid){

        $topicStats = [];
        // Fetch question bank details by ID
        $allTopicsBySubject = Topic::where('subject_id', $subjectid)->get();

        if ($allTopicsBySubject) {
            
            foreach ($allTopicsBySubject as $topic) {
                
                // Fetch all questions for this subject
                $questions = DB::table('questions')
                    ->select('id as question_id', 'topic_id')
                    ->where('topic_id', $topic->id)
                    ->get();

                // Fetch quiz data for the questions in this subject
                $quizData = DB::table('quizzes')
                    ->leftJoin('quiz_question', 'quizzes.id', '=', 'quiz_question.quiz_id')
                    ->select('quiz_question.question_id', 'quiz_question.is_correct', 'quiz_question.is_attempted', 'quiz_question.selected_answer')
                    ->whereIn('quiz_question.question_id', $questions->pluck('question_id')->toArray())
                    ->where('quizzes.status', 'completed')
                    ->get()
                    ->groupBy('question_id');

                // Initialize counters
                $total_questions = count($questions);
                $correct_answers = 0;
                $incorrect_answers = 0;
                $omitted = 0;
                $usage = 0;
                foreach ($questions as $question) {
                    $questionId = $question->question_id;
                    $is_correct = 0;
                    $is_attempted = 0;
                    $not_attempted = 0;
                    
                    if (isset($quizData[$questionId])) {
                        foreach ($quizData[$questionId] as $quizEntry) {
                            if ($quizEntry->is_correct == 1) {
                                $is_correct = 1;
                            }
                            if ($quizEntry->is_attempted == 1) {
                                $is_attempted = 1;
                            }
                            if ($quizEntry->selected_answer == null && $quizEntry->is_attempted == 0) {
                                $not_attempted = 1;
                            }
                        }
                    }
                    if($is_attempted == 1){
                        $usage++;
                    }
                    if ($is_correct == 1 && $is_attempted == 1) {
                        $correct_answers++;
                    } elseif ($is_correct == 0 && $is_attempted == 1) {
                        $incorrect_answers++;
                    } elseif ($not_attempted == 1 && $is_attempted == 0) {
                        $omitted++;
                    }
                }
                $topicStats[$topic->id] = [
                    'topic_id' => $topic->id,
                    'topic_name' => $topic->name,
                    'usage'=>$usage,
                    'total_questions' => $total_questions,
                    'correct_answers' => $correct_answers,
                    'incorrect_answers' => $incorrect_answers,
                    'not_attempted' => $omitted,
                ];
            }
        }
        return view('analytics_by_subject', compact('topicStats'));
    }
}


