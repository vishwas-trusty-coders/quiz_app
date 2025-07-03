<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    // Start quiz
    public function startQuiz($quizId)
    {
        $quiz = Quiz::with('questionBank')->findOrFail($quizId);

        // Check if the quiz is already started or completed
        if ($quiz->status === 'in_progress' || $quiz->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Quiz already started or completed.']);
        }

        // Update quiz status to 'in_progress'
        $quiz->status = 'in_progress';
        $quiz->save();

        // Fetch all questions related to this quiz
        $questions = $quiz->questions()
            ->withPivot('is_attempted', 'selected_answer', 'is_correct')
            ->get();

        $questionTimes = $questions->mapWithKeys(function ($question) {
            return [$question->id => $question->pivot->time_spent ?? 0]; // Default to 0 if no time is recorded
        });

        $questionBankName = $quiz->questionBank->name ?? 'Unknown' ;

        return view('quiz', compact('quiz', 'questions', 'questionTimes', 'questionBankName'));
    }

    // Resume quiz
    public function resumeQuiz($quizId)
    {
        $quiz = Quiz::with('questionBank')->findOrFail($quizId);

        // Ensure the quiz is in progress
        if ($quiz->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Quiz cannot be resumed or has already been completed.']);
        }

        // Fetch all questions for the quiz with their current status
        $questions = $quiz->questions()
            ->withPivot('is_attempted', 'selected_answer', 'is_correct')
            ->get();

        $questionTimes = $questions->mapWithKeys(function ($question) {
            return [$question->id => $question->pivot->time_spent ?? 0]; // Default to 0 if no time is recorded
        });

        $questionBankName = $quiz->questionBank->name ?? 'Unknown' ;

        return view('quiz', compact('quiz', 'questions','questionTimes', 'questionBankName'));
    }

    // Attempt quiz (save the user's answer and update attempt status)
    public function attemptQuiz(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        // Validate request
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'selected_answer' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid input.']);
        }

        // Save the user's selected answer and mark it as attempted
        $quiz->questions()->updateExistingPivot($request->question_id, [
            'selected_answer' => $request->selected_answer,
            'is_attempted' => true,
            'is_correct' => ($request->selected_answer == $request->correct_answer),
            'time_spent' => $request->time_spent
        ]);

        return response()->json(['success' => true, 'message' => 'Answer saved!']);
    }

    // End quiz (forcefully mark quiz as completed)
    public function endQuiz(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        // Ensure the quiz is in progress or attempted but not completed
        // if ($quiz->status !== 'in_progress' && $quiz->status !== 'attempted') {
        //     return response()->json(['success' => false, 'message' => 'Quiz cannot be ended.']);
        // }

        $questions = $quiz->questions()->withPivot('selected_answer', 'is_attempted', 'is_correct')->get();
        $totalQuestions=0;
        $correctAnswersCount  = 0;
        foreach($questions as $question){
            if($question->pivot->is_correct == 1){
                $correctAnswersCount ++;
            }
            $totalQuestions++;
        }
        $score = ($correctAnswersCount/$totalQuestions) * 100;

        // Mark the quiz as completed
        $quiz->score = $score;
        $quiz->status = 'completed';
        $quiz->timer = $request->total_time;
        $quiz->save();

        return response()->json(['success' => true, 'message' => 'Quiz ended successfully!']);
    }

    public function showQuizResult($quizId)
    {
        // Fetch quiz details
        $quiz = Quiz::findOrFail($quizId);
        $score = $quiz->score;
        $totalQuestions = count($quiz->questions);
        $totalCorrectAnswer = 0;
        $totalIncorrectAnswer = 0;
        $totalNotAttempted = 0;
        $quiztime = $quiz->timer;
        $hours = floor($quiztime / 3600);
        $minutes = floor(($quiztime / 60) % 60);
        $seconds = $quiztime % 60;

        $quiztotaltime = $hours. ' hrs '. $minutes. ' mins '. $seconds. ' sec';
        foreach ($quiz->questions as $question) {
            if ($question->pivot->is_correct == 1) {
                $totalCorrectAnswer++;
            }elseif(($question->pivot->is_correct == 0 )&& ($question->pivot->is_attempted == 1)){
                $totalIncorrectAnswer++;
            }else{
                $totalNotAttempted++;
            }
        }

        return view('quizresult', compact('quiz', 'totalCorrectAnswer', 'score', 'totalQuestions', 'totalIncorrectAnswer', 'quiztotaltime', 'totalNotAttempted'));
    }

    public function toggleFlag(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questionId = $request->question_id;
        $isFlagged = $request->is_flagged;

        $quiz->questions()->updateExistingPivot($request->question_id, [
            'is_flagged' => $isFlagged
        ]);

        return response()->json(['success' => true, 'message' => 'Flag status updated successfully.']);
    }

    public function quizPause(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $paused_time = $request->paused_time;

        $quiz->timer = $paused_time;
        $quiz->save();

        return response()->json(['success' => true, 'message' => 'Time saved successfully.']);
    }

    public function destroy($id)
    {
        Quiz::find($id)->delete();
        return redirect()->back()->withErrors(['error' => 'Deleted successfully']);
    }
}
