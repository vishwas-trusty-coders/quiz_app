<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\OurserviceController;
use App\Http\Controllers\MeettheteamController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\ServicessinglepageController;
use App\Http\Controllers\QuestionbankController;
use App\Http\Controllers\CreatequizController;
use App\Http\Controllers\BuycreditController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TimedQuizController;
use App\Http\Controllers\GoogleContactsController;
use App\Http\Controllers\PerformanceAnalyticsController;

// Route::get('/', [HomepageController::class, 'index'])->name('homepage');
// Route::get('/our-services', [OurserviceController::class, 'index'])->name('our-services');
// Route::get('/meet-the-team', [MeettheteamController::class, 'index'])->name('meet-the-team');
// Route::get('/podcast', [PodcastController::class, 'index'])->name('podcast');
// Route::get('/service/{slug}', [ServicessinglepageController::class, 'index'])->name('service.show');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/questionbank', [QuestionbankController::class, 'index'])->name('questionbank');
    Route::get('/createquiz/{id}', [CreatequizController::class, 'createQuiz'])->name('quiz.create');
    Route::get('buy_credit', [BuycreditController::class, 'index'])->name('buy_credit');
    Route::post('/buy_credit/payment', [BuyCreditController::class, 'payment'])->name('buy.credit.payment');
    Route::get('/buy_credit/success', [BuyCreditController::class, 'success'])->name('buy.credit.success');
    Route::get('/buy_credit/cancel', [BuyCreditController::class, 'cancel'])->name('buy.credit.cancel');
    Route::get('/package', [QuestionbankController::class, 'questionBankList'])->name('questionbank_package');
    Route::post('/purchase-question/{id}', [QuestionBankController::class, 'purchase'])->name('purchase.question');
    Route::post('quiz/store', [CreatequizController::class, 'storeQuiz'])->name('quiz.store');
    Route::get('/quiz/start/{quizId}', [QuizController::class, 'startQuiz'])->name('quiz.start');
    Route::get('/quiz/resume/{quizId}', [QuizController::class, 'resumeQuiz'])->name('quiz.resume');
    Route::post('/quiz/attempt/{quizId}', [QuizController::class, 'attemptQuiz'])->name('quiz.attempt');
    Route::get('/quiz/delete/{quizId}', [QuizController::class, 'destroy'])->name('quiz.delete');
    Route::post('/quiz/end/{quizId}', [QuizController::class, 'endQuiz'])->name('quiz.end');
    Route::get('/quiz/{quizId}/result', [QuizController::class, 'showQuizResult'])->name('quiz.result');
    Route::post('/quiz/flag/{quizId}', [QuizController::class, 'toggleFlag'])->name('quiz.flag');
    Route::post('/quiz/pause/{quizId}', [QuizController::class, 'quizPause'])->name('quiz.pause');
    Route::post('/quiz/getquestioncount/', [CreatequizController::class, 'getQuestionCount'])->name('question_count');
    Route::post('/quiz/get-topic-question-count/', [CreatequizController::class, 'getTopicQuestionsCount'])->name('get.topics.questions.count');

    //for timed quiz
    Route::get('/timedquiz/start/{quizId}', [TimedQuizController::class, 'startQuiz'])->name('timedquiz.start');
    Route::get('/timedquiz/resume/{quizId}', [TimedQuizController::class, 'resumeQuiz'])->name('timedquiz.resume');
    Route::post('/timedquiz/attempt/{quizId}', [TimedQuizController::class, 'attemptQuiz'])->name('timedquiz.attempt');
    Route::post('/timedquiz/end/{quizId}', [TimedQuizController::class, 'endQuiz'])->name('timedquiz.end');
    Route::post('/timedquiz/flag/{quizId}', [TimedQuizController::class, 'toggleFlag'])->name('timedquiz.flag');
    Route::post('/timedquiz/pause/{quizId}', [TimedQuizController::class, 'quizPause'])->name('timedquiz.pause');

    //For Analytics 
    Route::get('/analytics', [PerformanceAnalyticsController::class, 'getQuizData'])->name('performance.analytics');
    Route::get('/analytics/questionbank/{questionBankId}', [PerformanceAnalyticsController::class, 'getAnalyticsByQuestionBank'])->name('performance.question_bank');
    Route::get('/analytics/subjects/{subjectId}', [PerformanceAnalyticsController::class, 'getAnalyticsBySubject'])->name('performance.subject');
});
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/callback', [GoogleContactsController::class, 'handleCallback']);
Route::post('/save-to-google-group', [GoogleContactsController::class, 'store'])->name('save-to-google-group');
Route::post('/add-to-contacts', [GoogleContactsController::class, 'addToContacts'])->name('add-to-contacts');

require __DIR__.'/auth.php';


Route::get('/dbinfo', function(){
    // $databaseName = \DB::connection();
    // dd($databaseName);

    // \DB::statement("ALTER TABLE `quizzes` ADD `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `status`");
    // \DB::statement("ALTER TABLE `quizzes` DROP `deleted_at`");
    dd("done");
});