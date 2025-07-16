<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuestionBank;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Support\Facades\Validator;

class CreatequizController extends Controller
{
    public function index()
    {
        return view('createquiz');
    }

    public function createQuiz(Request $request)
    {
        $questionBankId = $request->id; // ID from URL
        $questionBank = QuestionBank::findOrFail($questionBankId);

        // Retrieve subject IDs and topic IDs stored as arrays
        // $subjectIds = $questionBank->subjects; // Assuming JSON array
        // $topicIds = $questionBank->topics;
        // $tags = $questionBank->tags;

        $subjectIds = $questionBank->subjects ?? [];
        $topicIds = $questionBank->topics ?? [];
        $tags = $questionBank->tags ?? [];
        // dd($subjectIds);
        // Get all subjects and their respective question counts
        $subjects = Subject::whereIn('id', $subjectIds)->get()->mapWithKeys(function ($subject) use ($tags) {
            $questionCount = Question::where('subject_id', $subject->id)
                ->where(function ($query) use ($tags) {
                    foreach ($tags as $tagId) {
                        $query->orWhereJsonContains('tag_ids', $tagId);
                    }
                })
                ->count();

            return [$subject->id => ['name' => $subject->name, 'count' => $questionCount]];
        });
        $topics = array();
        $originalTopics = array();
        // Get all topics and their respective question counts
        $topicSubjects = Topic::with('questions')->whereIn('id', $topicIds)
            ->whereIn('subject_id', $subjectIds) // Ensure topics belong to the selected subjects
            ->get()->groupBy('subject_id') // Group topics by `subject_id`
            ->mapWithKeys(function ($group, $subjectId) use ($tags) {
                return [
                    $subjectId => $group->map(function ($topic) use ($tags) {
                        $questionCount = Question::where('topic_id', $topic->id)
                            ->where(function ($query) use ($tags) {
                                foreach ($tags as $tagId) {
                                    $query->orWhereJsonContains('tag_ids', $tagId);
                                }
                            })
                            ->count();

                        return [
                            'subject_id' => $topic->subject_id,
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'count' => $questionCount,
                        ];
                    })->values()
                ];
            });
        $existingTopics = array();
        $c = 0;
        $i = 0;
        foreach ($topicSubjects as $t) {
            foreach ($t as $topic) {
                if (array_key_exists($topic['name'], $existingTopics)) {
                    // echo "exists ";
                    /* updated count only if element exists again */
                    array_push($topics[$existingTopics[$topic['name']]]['count'], $topic['count']);
                    array_push($topics[$existingTopics[$topic['name']]]['subject_id'], $topic['subject_id']);
                    array_push($topics[$existingTopics[$topic['name']]]['id'], $topic['id']);
                } else {
                    $existingTopics[$topic['name']] = $c;
                    $topics[$c]['subject_id'] = array($topic['subject_id']);
                    $topics[$c]['id'] = array($topic['id']);
                    $topics[$c]['name'] = $topic['name'];
                    $topics[$c]['count'] =  array($topic['count']);
                    $c++;
                }

                $originalTopics[$i]['subject_id'] = $topic['subject_id'];
                $originalTopics[$i]['id'] = $topic['id'];
                $originalTopics[$i]['name'] = $topic['name'];
                $originalTopics[$i]['count'] = $topic['count'];
                $i++;
            }
        }

        // dd($subjects);

        return view('createquiz', [
            'topicSubjects' => $topicSubjects,
            'questionBank' => $questionBank,
            'subjects' => $subjects,
            'existingTopics' => $existingTopics,
            'topics' => $topics,
            'originalTopics' => $originalTopics,
            'tags' => $tags
        ]);
    }

    public function storeQuiz(Request $request)
    {
        $topic = [];
        foreach ($request->topic as $tp) {
            // dump($tp);
            if (strpos($tp, ',') !== false) {
                $val = explode(",", $tp);
                $topic = array_merge($topic, $val);
                // array_merge($topic,$tp);
            } else {
                $topic[] = $tp;
            }
        }
        // dd($topic);

        $request->request->add(['topic' => $topic]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'question_bank_id' => 'required|exists:question_banks,id',
            'tutor_mode' => 'nullable',
            'timed_mode' => 'nullable',
            'subject' => 'required|array',
            'topic' => 'required',
            'question_status' => 'required|array',
            'question_status.*' => 'in:all,new,flagged,incorrect,correct',
            'question_quantity' => 'required|integer|min:1',
        ]);
        // If validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => implode("\n\n", $validator->errors()->all())]);
        }

        // Access validated data
        $validated = $validator->validated();
        $user = auth()->user();

        //dd($validated['topic']);

        // Start building the query
        $questionsQuery = Question::query();

        // Filter by subjects and topics
        $questionsQuery->whereIn('subject_id', $validated['subject'])
            ->whereIn('topic_id', $validated['topic']);
        $tags = json_decode($request->tags);
        if (!empty($tags)) {
            $questionsQuery->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tag_ids', $tag);
                }
            });
        }

        // Check if "all" is included in question_status
        if (in_array('all', $validated['question_status'])) {
            // If "all" is selected, do not apply any additional filters
            $questions = $questionsQuery->inRandomOrder()
                ->limit($validated['question_quantity'])
                ->get();
        } else {
            // Apply filters for other statuses
            $questionsQuery->where(function ($query) use ($validated, $user) {
                foreach ($validated['question_status'] as $status) {
                    if ($status === 'new') {
                        $query->orWhereNotIn('id', function ($subQuery) use ($user) {
                            $subQuery->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id);
                        });
                    } elseif ($status === 'incorrect') {
                        $query->orWhereIn('id', function ($subQuery) use ($user) {
                            $subQuery->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_correct', false)
                                ->where('is_attempted', true);
                        });
                    } elseif ($status === 'correct') {
                        $query->orWhereIn('id', function ($subQuery) use ($user) {
                            $subQuery->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_correct', true)
                                ->where('is_attempted', true);
                        });
                    } elseif ($status === 'flagged') {
                        $query->orWhereIn('id', function ($subQuery) use ($user) {
                            $subQuery->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_flagged', true);
                        });
                    }
                }
            });

            // Fetch the questions
            $questions = $questionsQuery->inRandomOrder()
                ->limit($validated['question_quantity'])
                ->get();
        }

        if ($questions->count() != 0) {
            // Create the quiz
            $quiz = Quiz::create([
                'name' => $validated['name'],
                'user_id' => $user->id,
                'question_bank_id' => $validated['question_bank_id'],
                'tutor_mode' => $validated['tutor_mode'],
                'timed_mode' => $validated['timed_mode'],
                'subject_ids' => json_encode($validated['subject']),
                'topic_ids' => json_encode($validated['topic']),
                'question_status' => json_encode($validated['question_status']),
                'question_quantity' => $validated['question_quantity'],
                'timer' => 0,
                'remaining_time' => ($validated['timed_mode'] == 'on') ? ($validated['question_quantity'] * 90) : 0,
                'total_time' => ($validated['timed_mode'] == 'on') ? ($validated['question_quantity'] * 90) : 0
            ]);
            // Attach the questions to the quiz
            $quiz->questions()->attach($questions->pluck('id')->mapWithKeys(function ($id) {
                return [
                    $id => [
                        'is_correct' => false,
                        'is_attempted' => false,
                        'is_flagged' => false,
                    ],
                ];
            }));
        } else {
            $quiz = [];
        }

        if (!empty($quiz)) {
            return response()->json(['success' => true, 'message' => 'Quiz created successfully!', 'quiz_id' => $quiz->id, 'mode' => $validated['timed_mode']]);
        } else {
            return response()->json(['success' => false, 'errors' => 'Failed to create quiz. Try another option of question status.']);
        }
    }

    public function getQuestionCount(Request $request)
    {
        $topic = [];
        foreach ($request->topic as $tp) {
            // dump($tp);
            if (strpos($tp, ',') !== false) {
                $val = explode(",", $tp);
                $topic = array_merge($topic, $val);
                // array_merge($topic,$tp);
            } else {
                $topic[] = $tp;
            }
        }
        $request->request->add(['topic' => $topic]);
        // dump('final');
        // dd($request->topic);
        $user = auth()->user();
        $count = 0;

        // Initialize the query for questions
        $query = Question::query();

        // Filter by subject and topic
        if (!empty($request->subject)) {
            $query->whereIn('subject_id', $request->subject);
        }

        if (!empty($request->topic)) {
            //$topic = explode(",", $request->topic);
            $query->whereIn('topic_id', $request->topic);
        }
        $tags = json_decode($request->tags);
        if (!empty($tags)) {
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tag_ids', $tag);
                }
            });
        }
        // Check for "all" status
        if (in_array('all', $request->question_status)) {
            // If "all" is selected, include all questions for the subjects and topics
            $count = $query->count();
        } else {
            // Apply specific status filters
            $query->where(function ($q) use ($request, $user) {
                foreach ($request->question_status as $status) {

                    if ($status === 'new') {

                        $q->orWhereNotIn('id', function ($query) use ($user) {
                            $query->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id);
                        });
                    } elseif ($status === 'incorrect') {

                        $q->orWhereIn('id', function ($query) use ($user) {
                            $query->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_correct', false)
                                ->where('is_attempted', true);
                        });
                    } elseif ($status === 'correct') {

                        $q->orWhereIn('id', function ($query) use ($user) {
                            $query->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_correct', true)
                                ->where('is_attempted', true);
                        });
                    } elseif ($status === 'flagged') {

                        $q->orWhereIn('id', function ($query) use ($user) {
                            $query->select('question_id')
                                ->from('quiz_question')
                                ->join('quizzes', 'quizzes.id', '=', 'quiz_question.quiz_id')
                                ->where('quizzes.user_id', $user->id)
                                ->where('is_flagged', true);
                        });
                    }
                }
            });

            // Count the filtered questions
            $count = $query->count();
        }

        return response()->json(['status' => $request->question_status, 'count' => $count]);
    }


    /** Ajax call on create quiz page to fetch count related to questions */
    public function getTopicQuestionsCount(Request $request)
    {
        auth()->user();
        $results=[];
        if (!empty($request->subject)) {
            foreach ($request->subject as $subject) {
                $args = [
                    'subject' => $subject,
                    'tags' => json_decode($request->tags, true)
                ];
                //get topic wise data
                $result = \App\Models\Topic::withCount([
                    'questions' => function ($q) use ($args) {
                        foreach ($args['tags'] as $tag) {
                            $q->whereJsonContains('tag_ids', $tag);
                        }
                    }
                ])
                ->where('subject_id', $subject)->get()->toArray();
                $results=array_merge($results,$result);
            }
            $count = array_column($results, 'question_count');
            return response()->json(['status' => true, 'data' => json_encode($results), 'count' => $count]);
        } else {
            return response()->json(['status' => true, 'data' => json_encode([]), 'count' => 0]);
        }
    }
}
