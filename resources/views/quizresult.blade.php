<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $quiz->name }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>
<div id="quiz-result-section">
    <div class="quiz-result-container">
        <!-- Quiz Result Section -->
        <div class="text-center mb-8">
            
        </div>

        <!-- Result Summary Section -->
        <div class="circular-progress-box">
            <!-- Circular Progress -->
                <div class="relative quiz-card">
                    <h2 class="text-lg font-semibold mb-4">Quiz Performance</h2>
                    <div class="progress-ring" style="--progress: {{ $score }};">
                        <svg width="120" height="120" viewBox="0 0 120 120">
                            <circle class="bg" cx="60" cy="60" r="50" style="stroke-width: 10;"></circle>
                            <circle class="fg" cx="60" cy="60" r="50" style="stroke-width: 10; stroke-dasharray: 314.16; stroke-dashoffset: 78.54;"></circle>
                        </svg>
                        <div class="percentage">{{ $score }}% Correct Answer</div>
                    </div>
                    <p class="score_compare"><strong>Correct / Total Questions:</strong> {{ $totalCorrectAnswer }}/{{ $quiz->question_quantity }}</p>
                </div>
            

            <!-- Score Summary -->
            
                <div class="your-score-sec">
                    <h2 class="text-lg font-semibold mb-4">Your Score</h2>
                    <ul class="space-y-2">
                        <li><div><i class="fa-regular fa-clock clock-ic"></i> Total Time:</div><div> {{ $quiztotaltime }}</div></li>
                        <li><div>Total Number of Questions:</div> <div>{{ $quiz->question_quantity }}</div></li>
                        <li><div><i class="fa-solid fa-check check-ic"></i> Total Correct Answers:</div> <div>{{ $totalCorrectAnswer }}</div></li>
                        <li><div><i class="fa-solid fa-xmark close-ic"></i> Total Incorrect Answers:</div> <div>{{ $totalIncorrectAnswer }}</div></li>
                        <li><div><i class="fa-solid fa-circle-minus"></i> Not Attempted Questions:</div> <div>{{ $totalNotAttempted }}</div></li>
                    </ul>
                </div>           
        </div>

        <!-- Question Details Table -->
        <div class="table-container-set">
         <div class="responsive-table-wrapper">
            <table class="">
                <thead class="">
                    <tr>
                        <th class="">Q. No</th>
                        <th class="">Question</th>
                        <th class="">Subject/Topic</th>
                        <th class="">Time Spent</th>
                        <th class="">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quiz->questions as $question)
                        <tr class="border-set">
                            <td class=""> 
                                @if(isset($question->pivot->is_correct) && ($question->pivot->is_correct==1))
                                    <span class="check-ic"><i class="fa-solid fa-check check-ic"></i></span>
                                @elseif(($question->pivot->is_correct==0) && ($question->pivot->is_attempted==1))
                                    <span class="cancel-ic"><i class="fa-solid fa-xmark close-ic"></i></span>
                                @else
                                    <span class="not-attempted"><i class="fa-solid fa-circle-minus"></i></span>
                                @endif
                                {{ $loop->iteration }}
                                @if($question->pivot->is_flagged)
                                    <span class="text-red-500 flag_icon"><i class="fa-solid fa-flag"></i></span>
                                @endif
                            </td>
                            <td class="question-descption">{!! mb_substr(strip_tags($question->question), 0, 30) . '...' !!}</td>
                            <td class="">{{ $question->subject->name }}<br><span>{{ $question->topic->name }}</span></td>
                            <td class="">{{ $question->pivot->time_spent }} sec</td>
                            <td class=" text-center">
                                <button class="text-blue-500 eys-icon" onclick="toggleDetails('{{ $loop->iteration }}')"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tr>
                         <!-- Hidden row for details -->
                         <tr id="details-row-{{ $loop->iteration }}" class="hidden td-content-div">
                            <td colspan="6" class="border border-gray-300 px-4 py-2 bg-gray-50">
                            <div class="quest_desc">{!! $question->question !!}</div>
                            <!-- Display options for the question -->
                            <ul class="table-question-list">
                                @php
                                    $attemptedAnswer = $question->pivot->selected_answer ?? null;
                                    $isAttempted = $question->pivot->is_attempted ?? false;
                                    $correct_answer = $question->correct_answer;
                                    $answer_options = $question->options;
                                @endphp
                                @if(!empty($answer_options))
                                    @foreach($answer_options as $index => $option)
                                        @php
                                            $optionKey = chr(65 + $index); // Convert index to option letter A, B, C, D...
                                            $isCorrect = $attemptedAnswer == $optionKey && $attemptedAnswer == $correct_answer;
                                            $isIncorrect = $attemptedAnswer == $optionKey && $attemptedAnswer != $correct_answer;
                                            $class = '';
                                            if ($isAttempted == 1) {
                                                if ($optionKey == $correct_answer) {
                                                    $class .= 'correct';
                                                } elseif ($attemptedAnswer == $optionKey) {
                                                    $class .= 'incorrect';
                                                }
                                            }
                                        @endphp
                                        <li class="{{ $class }}">
                                            <input type="radio" name="question-{{ $question->id }}" value="{{ $optionKey }}"
                                                {{ $attemptedAnswer == $optionKey ? 'checked' : '' }} 
                                                {{ $isAttempted ? 'disabled' : '' }} >
                                                {{ $optionKey }}. {{ $option['option'] }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                                <strong>Selected Answer:</strong>@if(!empty($question->pivot->selected_answer)) {{ $question->pivot->selected_answer }} @else {{ 'Not attempted' }} @endif<br>
                                <strong>Correct Answer:</strong> {{ $question->correct_answer }}<br>
                                <div class="Explanation-box"><strong>Explanation:</strong> {!! $question->explanation !!}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
</div>
        </div>

        <!-- Back Button -->
        <div class="back-btn">
            <a href="{{ route('dashboard') }}" class="back-question">
                Back to Question Bank
            </a>
        </div>
    </div>
</div>

    <script>
        function toggleDetails(rowId) {
            const detailsRow = document.getElementById('details-row-' + rowId);
            const questionRow = document.getElementById('question-row-' + rowId);
            
            // Toggle the visibility of the details row
            detailsRow.classList.toggle('hidden');

            // Optionally, scroll to the details row
            detailsRow.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</x-app-layout>
