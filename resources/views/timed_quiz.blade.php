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
@php 
$flagstatus = [];
$seconds = $quiz->remaining_time;

// Convert seconds to hours, minutes, and seconds
$hours = floor($seconds / 3600);
$minutes = floor(($seconds % 3600) / 60);
$remainingSeconds = $seconds % 60;

// Format the result as h:m:s
$timeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
@endphp
    <!-- quiz.blade.php -->
    <div id="quiz-container" class="">
        <!-- Sidebar with question numbers -->
        <div id="question-sidebar" class="">
            <ul>
                @foreach($questions as $index => $question)
                    @php
                        $attemptedAnswer = $question->pivot->selected_answer ?? null;
                        $isAttempted = $question->pivot->is_attempted ?? false;
                        $isCorrect = $attemptedAnswer === $question->correct_answer;
                        
                        $isFlagged = $question->pivot->is_flagged;
                    @endphp
                    <li id="question-sidebar-item-{{ $index }}" class="flex items-center">
                        
                        @if($isAttempted)
                            @if($isCorrect)
                                <span class="text-green-500"><i class="fa-solid fa-check"></i></span>
                            @else
                                <span class="text-red-500"><i class="fa-solid fa-xmark"></i></span>
                            @endif
                        @else
                        <span class="text-red-500"><i class="fa-regular fa-circle"></i></span>
                        @endif
                        <a href="javascript:void(0);" onclick="goToQuestion({{ $index }})" class="mr-2"> 
                            {{ $index + 1 }}
                        </a>
                        <span class="flag_append">
                        @if($isFlagged)
                            <span class="text-red-500"><i class="fa-solid fa-flag"></i></span>
                        @endif
                    </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Main quiz area -->
        <div id="quiz-content" class="">
            <div class="quiz_head">
                <div>
                    <h3>{{ $quiz->name }}</h3>
                    <p> Question Mode: Timed</p>
                </div>
                <div class="timer-box">
                    <p><i class="fa-regular fa-clock"></i> Time: <span id="quiz-timer">{{ $timeFormatted }}</span></p>
                    
                    <p id="question-progress">Question 1 of {{ count($questions) }}</p>
                </div>
            </div>
            @foreach($questions as $index => $question)            
                <div class="question" id="question-{{ $question->id }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                    <div class="edit_buttns">
                        <button onclick="applyHighlight()"><i class="fa-solid fa-highlighter"></i></button>
                    </div>
                    <div class="quest_desc editableText" contenteditable="true">{!! $question->question !!}</div>
                    <!-- Display options for the question -->
                    <div class="strike_buttns">
                        <button onclick="applyStrikethrough()"><i class="fa-solid fa-strikethrough"></i></button>
                    </div>
                    <ul class="question-list">
                        @php
                            $attemptedAnswer = $question->pivot->selected_answer ?? null;
                            $isAttempted = $question->pivot->is_attempted ?? false;
                            $correct_answer = $question->correct_answer;
                            $flagstatus[$question->id] = $question->pivot->is_flagged == 1 ? true : false;
                            $options = $question->options;
                        @endphp
                        @if(!empty($options))
                            @foreach ($options as $key => $option)
                                @php
                                    $optionKey = chr(65 + $key);
                                @endphp
                                <li>
                                    <label style="display: block; width: 100%; cursor: pointer;">
                                        <input type="radio" name="question-{{ $question->id }}" value="{{ $optionKey }}" 
                                        {{ $attemptedAnswer == $optionKey ? 'checked' : '' }} > {{ $optionKey }}. {{ $option['option'] }}
                                    </label>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    
                </div>
            @endforeach 
        </div>
    </div>
    <!-- Navigation buttons -->
    <div class="four-btn-bottom">
        <div class="flag-checkbox-container">
            <label>
                <input type="checkbox" name="flag_question" id="flag-checkbox" onchange="toggleFlagCheckbox(this.checked)">
                <i class="fa-solid fa-flag"></i><span class="flag_label">Flag Question</span>
            </label>
        </div>
        <div> 
            <div id="submit_quiz_loader" style="display:none;">
                <img src="{{ asset('img/loader.gif') }}" alt="loader">
            </div>                     
            <button id="submit-quiz-button" onclick="submitQuiz()">Submit Quiz</button>
            <button id="prev-button" onclick="prevQuestion()">Previous</button>
            <button id="next-button" onclick="nextQuestion()">Next </button>
            <button id="pause-timer" onclick="pauseTimer()">Pause</button>
        </div>
    </div>

    <!-- Success Modal Popup -->
    <div id="success-popup" style="display: none; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="background: white; margin: 20% auto; padding: 20px; text-align: center; border-radius: 8px; max-width: 300px;">           
            <p id="popup-message">Are you done?/Submit Quiz</p>
            <div class="popup_buttons">
                <button id="close_popup" style="background-color:rgb(236, 26, 11); color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">
                    No
                </button>
                
                <button id="submit_quiz" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">
                    Yes
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentQuestionIndex = 0;
        const quizId = {{ $quiz->id }};
        const questions = @json($questions);
        const totalQuestions = {{ count($questions) }};
        const flagStatus = @json($flagstatus);
        document.getElementById('flag-checkbox').checked = flagStatus[questions[currentQuestionIndex].id] || false;
        let elapsedTime = {{ $quiz->timer !== null ? $quiz->timer : 0 }};
        let total_time = {{ $quiz->total_time !== null ? $quiz->total_time : 0 }};
        let countdownTime = {{ $quiz->remaining_time !== null ? $quiz->remaining_time : 0 }}; // Initialize with total countdown time (in seconds)
        let countdownStartTime = 0;
        let countdownTimerInterval;
        let elapsedSinceStart = 0;
        let remainingTime = 0;
        let questionStartTime = 0;
        let questionElapsedTime = 0;
        const questionTimes = @json($questionTimes);

        // Function to toggle flag status
        function toggleFlagCheckbox(isChecked) {
            const currentQuestionId = questions[currentQuestionIndex].id;
            flagStatus[currentQuestionId] = isChecked;
            saveFlagStatus(currentQuestionId, isChecked);
             // Update UI (e.g., add/remove flag icon in sidebar)
           updateSidebarFlag(currentQuestionIndex, isChecked);
        }

        // Update the sidebar flag indicator
        function updateSidebarFlag(questionId, isFlagged) {
            const sidebarItem = document.querySelector('#question-sidebar-item-'+questionId);
            const flagIcon = sidebarItem.querySelector('.flag_append');

            if (isFlagged) {
                flagIcon.innerHTML ='<span class="text-red-500"><i class="fa-solid fa-flag"></i></span>';
            }else{
                flagIcon.innerHTML ='';
            }
        }

        // Save flag status to the server (Optional)
        function saveFlagStatus(questionId, isFlagged) {
            fetch(`{{ url('timedquiz/flag') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question_id: questionId,
                    is_flagged: isFlagged
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update flag status.');
                }
            })
            .catch(error => {
                console.error('Error updating flag status:', error);
            });
        }


        function updateQuestionProgress() {
            const progressElement = document.getElementById('question-progress');
            progressElement.textContent = `Question ${currentQuestionIndex + 1} of ${totalQuestions}`;
        }

        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                // Hide current question
                document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'none';
                submitAnswer(questions[currentQuestionIndex].id);
                currentQuestionIndex++;

                // Show next question
                document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'block';
                updateQuestionProgress();
                questionStartTime = elapsedSinceStart;
                document.getElementById('flag-checkbox').checked = flagStatus[questions[currentQuestionIndex].id] || false;
            }else{
                submitAnswer(questions[currentQuestionIndex].id);
                questionStartTime = elapsedSinceStart;
                $('#success-popup').fadeIn();
                $('#close_popup').on('click', function() {
                    $('#success-popup').fadeOut();
                });
                $('#submit_quiz').on('click', function() {
                    submitQuiz();
                    $('#success-popup').fadeOut();
                });
            }
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                // Hide current question
                document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'none';
                submitAnswer(questions[currentQuestionIndex].id);
                currentQuestionIndex--;

                // Show previous question
                document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'block';
                updateQuestionProgress();
                questionStartTime = elapsedSinceStart;
                document.getElementById('flag-checkbox').checked = flagStatus[questions[currentQuestionIndex].id] || false;
            }
        }

        function goToQuestion(index) {
            // Hide current question
            document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'none';
            submitAnswer(questions[currentQuestionIndex].id);
            currentQuestionIndex = index;

            // Show selected question
            document.getElementById('question-' + questions[currentQuestionIndex].id).style.display = 'block';
            updateQuestionProgress();
            questionStartTime = elapsedSinceStart;
            document.getElementById('flag-checkbox').checked = flagStatus[questions[currentQuestionIndex].id] || false;
        }

        function submitAnswer(questionId) {
            const selectedAnswer = document.querySelector(`input[name="question-${questionId}"]:checked`);
            const selectedAnswerValue = selectedAnswer ? selectedAnswer.value : ""; // Get the selected answer or set as empty
            const correctAnswer = questions.find(q => q.id === questionId).correct_answer;

            // Calculate time spent on this question
            const currentQuestionTime = elapsedSinceStart - questionStartTime; 
            questionTimes[questionId] = (questionTimes[questionId] || 0) + currentQuestionTime;

            // Update start time for the next question
            questionStartTime = elapsedSinceStart;

            // Submit the answer via API
            fetch(`{{ url('timedquiz/attempt') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question_id: questionId,
                    selected_answer: selectedAnswerValue,
                    correct_answer: correctAnswer,
                    time_spent: questionTimes[questionId]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data.message);
                }
            });
        }


        function submitQuiz() {
            // Submit the quiz via API to mark it as completed
            document.getElementById('submit_quiz_loader').style.display = 'block';
            fetch(`{{ url('timedquiz/end') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                    total_time: elapsedSinceStart + elapsedTime,  // Send the total time spent on the quiz
                    remaining_time : countdownTime - elapsedSinceStart
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    stopQuizTimer();
                    document.getElementById('submit_quiz_loader').style.display = 'block';
                    window.location.href = "{{ route('quiz.result', ['quizId' => '__quizId__']) }}".replace('__quizId__', quizId);
                } else {
                    alert(data.message);
                }
            });
        }

        /** timer feature js code */
        function stopQuizTimer() {
            elapsedSinceStart = 0;
        }

        function pauseTimer() {
            pausedTime = elapsedSinceStart + elapsedTime;
            savePausedTime(pausedTime);
        }

        function savePausedTime(pausedTime) {
            fetch(`{{ url('timedquiz/pause') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                    paused_time: pausedTime,
                    remaining_time : countdownTime - elapsedSinceStart
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                }else{
                    window.location.href = "{{ route('dashboard') }}";
                }
            });
        }

        window.addEventListener('unload', function (event) {
            pausedTime = elapsedSinceStart + elapsedTime;
            
            savePausedTimer(pausedTime);
        });

        function savePausedTimer(pausedTime) {
            const url = `{{ url('timedquiz/pause') }}/${quizId}`;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const data = JSON.stringify({
                quiz_id: quizId,
                paused_time: pausedTime,
                remaining_time : countdownTime - elapsedSinceStart,
                _token: token // Include CSRF token in the payload for Laravel
            });

            // Use navigator.sendBeacon to send data during unload
            if (navigator.sendBeacon) {
                const blob = new Blob([data], { type: 'application/json' });
                navigator.sendBeacon(url, blob);
            } else {
                // Fallback to synchronous XMLHttpRequest for older browsers
                const xhr = new XMLHttpRequest();
                xhr.open('POST', url, false); // `false` makes it synchronous
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
                xhr.send(data);
            }
        }

        function startCountdownTimer() {
            countdownStartTime = Date.now(); // Record the start time
            countdownTimerInterval = setInterval(updateCountdownTimer, 1000); // Update every second
        }

        // Update the countdown quiz timer every second
        function updateCountdownTimer() {
            elapsedSinceStart = Math.floor((Date.now() - countdownStartTime) / 1000); // Time passed since countdown started
            remainingTime = countdownTime - elapsedSinceStart; // Calculate remaining time

            if (remainingTime <= 0) {
                clearInterval(countdownTimerInterval); // Stop the timer when time runs out
                document.getElementById('quiz-timer').textContent = "00:00:00"; // Display 00:00:00 when time is up
                alert("Time's up!"); // Optional: Alert the user
                // Add logic here for what should happen when time is up (e.g., auto-submit quiz)
            } else {
                const hours = String(Math.floor(remainingTime / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((remainingTime % 3600) / 60)).padStart(2, '0');
                const seconds = String(remainingTime % 60).padStart(2, '0');
                document.getElementById('quiz-timer').textContent = `${hours}:${minutes}:${seconds}`;
            }
        }

        startCountdownTimer();

        document.querySelectorAll('.editableText').forEach(editable => {
            // Prevent deletion/modification except formatting
            editable.addEventListener('beforeinput', function (event) {
                if (event.inputType.startsWith('delete') || event.inputType.startsWith('insert')) {
                    event.preventDefault();
                }
            });
        });

        // Function to apply strikethrough
        function applyStrikethrough() {
            let selection = window.getSelection();
            if (selection.rangeCount) {
                let range = selection.getRangeAt(0);
                let parentElement = range.commonAncestorContainer.parentElement;

                // Check if the selected text is inside a question option
                if (parentElement.closest('.question-list')) {
                    let span = document.createElement('span');
                    span.style.textDecoration = 'line-through';
                    range.surroundContents(span);
                } else {
                    // alert("Strikethrough can only be applied to question options.");
                }
            }
        }

        // Function to apply highlight
        function applyHighlight(element) {
            let selection = window.getSelection();
            if (selection.rangeCount) {
                let range = selection.getRangeAt(0);
                let span = document.createElement('span');
                span.classList.add('highlighted');
                range.surroundContents(span);
            }
        }

        // Event delegation for buttons (works for multiple elements)
        document.addEventListener('click', function (event) {
            let editableElement = event.target.closest('.editableText');

            if (event.target.classList.contains('strikethroughBtn')) {
                if (editableElement && editableElement.closest('.question-list')) {
                    applyStrikethrough(editableElement);
                }
            }

            if (event.target.classList.contains('highlightBtn')) {
                applyHighlight(editableElement);
            }
        });

    </script>

</x-app-layout>
