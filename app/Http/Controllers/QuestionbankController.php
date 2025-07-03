<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionBank;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\packagePurchase as Purchase;
use Auth;

class QuestionbankController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'questionBank'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // foreach ($purchases as $purchase) {
        //     if ($purchase->questionBank) {
        //         // Fetch subjects with question count
        //        $subjectIds = is_array($purchase->questionBank->subjects)? $purchase->questionBank->subjects: json_decode($purchase->questionBank->subjects, true) ?? [];
        //         $subjects = Subject::whereIn('id', $subjectIds)->withCount(['questions'])->get();
        //         // $subjects = Subject::whereIn('id', $purchase->questionBank->subjects)
        //         //     ->withCount(['questions']) // Assuming a questions relationship exists in Subject
        //         //     ->get();

        //         $subjectNames = $subjects->map(function ($subject) {
        //             return $subject->name . ' (' . $subject->questions_count . ')';
        //         })->toArray();

        //         // Fetch topics with question count
        //       $topicIds = is_array($purchase->questionBank->topics)? $purchase->questionBank->topics: json_decode($purchase->questionBank->topics, true) ?? [];

        //         $topics = Topic::whereIn('id', $topicIds)->withCount(['questions'])->get();
        //         // $topics = Topic::whereIn('id', $purchase->questionBank->topics)
        //         //     ->withCount(['questions']) // Assuming a questions relationship exists in Topic
        //         //     ->get();

        //         $topicNames = $topics->map(function ($topic) {
        //             return $topic->name . ' (' . $topic->questions_count . ')';
        //         })->toArray();

        //         // Attach the names with counts
        //         $purchase->subject_names = implode(', ', $subjectNames);
        //         $purchase->topic_names = implode(', ', $topicNames);
        //     } else {
        //         // Fallback values
        //         $purchase->subject_names = 'N/A';
        //         $purchase->topic_names = 'N/A';
        //     }
        // }
        return view('questionbank', compact('purchases'));
    }

    public function questionBankList()
    {

        $questionBanks = QuestionBank::all();

        foreach ($questionBanks as $questionBank) {
            $subjectNamesQuery = Subject::whereIn('id', $questionBank->subjects);
            if ($subjectNamesQuery->exists()) {
                $subjectNames = $subjectNamesQuery->pluck('name')->toArray();
                if (!empty($subjectNames)) {
                    $topicNamesQuery = Topic::whereIn('id', $questionBank->topics);
                    if ($topicNamesQuery->exists()) {
                        $topicNames = $topicNamesQuery->pluck('name')->toArray() ?? [];
                        $questionBank->subject_names = implode(',', $subjectNames);
                        $questionBank->topic_names = implode(',', $topicNames);
                    }
                }
            }
        }
        return view('questionbank_package', compact('questionBanks'));
    }


    public function purchase(Request $request, $id)
    {
        $user = Auth::user();
        $questionBank = QuestionBank::findOrFail($id);
        // $Purchased_package = Purchase::pluck('question_bank_id')->toArray();
//add user id to purchase package
        $Purchased_package = Purchase::where('user_id', $user->id)
            ->pluck('question_bank_id')
            ->toArray();


        if (in_array($id, $Purchased_package)) {
            return redirect()->route('questionbank_package')->with('error', 'You have already purchased this package!');
        }

        // Check if the user has enough credits
        if ($user->credits >= $questionBank->credit) {
            // Deduct credits from the user
            $user->credits -= $questionBank->credit;
            $user->save();

            // Create a purchase record with subject and topic
            Purchase::create([
                'user_id' => $user->id,
                'question_bank_id' => $questionBank->id,
                'question_bank_name' => $questionBank->name,
                'subject' => json_encode($questionBank->subjects), // Convert array to JSON
                'topic' => json_encode($questionBank->topics),     // Convert array to JSON
                'credits_spent' => $questionBank->credit,
            ]);

            return redirect()->route('questionbank_package')->with('success', 'Purchase successful!');
        } else {
            return redirect()->route('questionbank_package')->with('error', 'Not enough credits!');
        }
    }
}
