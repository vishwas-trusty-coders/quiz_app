<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $questionBankName }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>
@php 
$flagstatus = [];
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
                    <p> Question Mode: Tutor</p>
                </div>
                <div class="timer-box">
                    <p><i class="fa-regular fa-clock"></i> Time: <span id="quiz-timer">00:00:00</span></p>
                    <p id="question-progress">Question 1 of {{ count($questions) }}</p>
                </div>
            </div>
            @foreach($questions as $index => $question)            
                <div class="question" id="question-{{ $question->id }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                    <div class="edit_buttns">
                        <button onclick="applyHighlight()"><i class="fa-solid fa-highlighter"></i></button>
                    </div>
                    <div class="quest_desc editableText" contenteditable="true">{{ strip_tags($question->question) }}</span></div>
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
                                    <label style="display: block; width: 100%; cursor: pointer;">
                                        <input type="radio" name="question-{{ $question->id }}" value="{{ $optionKey }}" 
                                            {{ $attemptedAnswer == $optionKey ? 'checked' : '' }} 
                                            {{ $isAttempted ? 'disabled' : '' }} 
                                            style="margin-right: 8px;">
                                        {{ $optionKey }}. {{ $option['option'] }}
                                    </label>
                                </li>

                            @endforeach
                        @endif
                    </ul>
                    <!-- Submit Button for each question, only available if not attempted -->
                    @if(!$isAttempted)
                        <button class="sbmtbtn" onclick="submitAnswer({{ $question->id }})">Submit</button>
                        <div id="submit_question_loader" style="display:none;">
                            <img src="{{ asset('img/loader.gif') }}" alt="loader">
                        </div> 
                    @else
                        <div id="feedback-{{ $question->id }}" class="feedback mt-4 text-gray-700">
                            <div class="correct-ans">
                                <div>
                                <div><i class="fa-solid fa-check"></i></div>
                                <div class="correctans-box">
                                    <div><strong>Correct Answer:</strong> {{ $correct_answer }}</div>
                                </div>
                            </div>
                                <div id="question-{{ $question->id }}-timer">
                                    <div><i class="fa-regular fa-clock"></i></div>
                                    <div>{{ $question->pivot->time_spent }} secs<br>
                                        Time Spent
                                    </div>
                                </div>
                            </div>
                            
                            <div class="Explanation-box"> 
                                 <p class="explanation-tit"><strong>Explanation:</strong> {!! $question->explanation !!}</p>
                            </div>
                        </div>
                    @endif
                    <span class="gray-text" style="float:right">ID:{{ $question->id }}</span> 
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
        // let questionStartTime = {{ $quiz->timer !== null ? $quiz->timer : 0 }};
        // let overallStartTime; 
        let overallTimerInterval;
        let overallElapsedTime = {{ $quiz->timer !== null ? $quiz->timer : 0 }};
        let elapsedTime = 0;
        let overallStartTime = Date.now();
        let questionStartTime = overallStartTime;
        const questionTimes = @json($questionTimes);

        // Function to toggle flag status
        function toggleFlagCheckbox(isChecked) {
            const currentQuestionId = questions[currentQuestionIndex].id;
            flagStatus[currentQuestionId] = isChecked;

            // Update UI (e.g., add/remove flag icon in sidebar)
           updateSidebarFlag(currentQuestionIndex, isChecked);

            // (Optional) Save flag status to the server
            saveFlagStatus(currentQuestionId, isChecked);
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
            fetch(`{{ url('quiz/flag') }}/${quizId}`, {
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
                let currentQuestionId = questions[currentQuestionIndex].id;
                let currentTimeSpent = Math.floor((Date.now() - questionStartTime) / 1000);  // Convert to seconds
                questionTimes[currentQuestionId] = (questionTimes[currentQuestionId] || 0) + currentTimeSpent;

                document.getElementById('question-' + currentQuestionId).style.display = 'none';
                currentQuestionIndex++;

                let newQuestionId = questions[currentQuestionIndex].id;
                document.getElementById('question-' + newQuestionId).style.display = 'block';
                updateQuestionProgress();

                // Reset the timer for the new question
                questionStartTime = Date.now();
                document.getElementById('flag-checkbox').checked = flagStatus[newQuestionId] || false;
            } else {
                // Last question handling
                let lastQuestionId = questions[currentQuestionIndex].id;
                let lastTimeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
                questionTimes[lastQuestionId] = (questionTimes[lastQuestionId] || 0) + lastTimeSpent;

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
                let currentQuestionId = questions[currentQuestionIndex].id;
                let currentTimeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
                questionTimes[currentQuestionId] = (questionTimes[currentQuestionId] || 0) + currentTimeSpent;

                document.getElementById('question-' + currentQuestionId).style.display = 'none';
                currentQuestionIndex--;

                let newQuestionId = questions[currentQuestionIndex].id;
                document.getElementById('question-' + newQuestionId).style.display = 'block';
                updateQuestionProgress();

                // Reset the timer for the new question
                questionStartTime = Date.now();
                document.getElementById('flag-checkbox').checked = flagStatus[newQuestionId] || false;
            }
        }

        function goToQuestion(index) {
            let currentQuestionId = questions[currentQuestionIndex].id;
            let currentTimeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
            questionTimes[currentQuestionId] = (questionTimes[currentQuestionId] || 0) + currentTimeSpent;

            document.getElementById('question-' + currentQuestionId).style.display = 'none';
            currentQuestionIndex = index;

            let newQuestionId = questions[currentQuestionIndex].id;
            document.getElementById('question-' + newQuestionId).style.display = 'block';
            updateQuestionProgress();

            // Reset the timer for the new question
            questionStartTime = Date.now();
            document.getElementById('flag-checkbox').checked = flagStatus[newQuestionId] || false;
        }

        function submitAnswer(questionId) {
            const selectedAnswer = document.querySelector(`input[name="question-${questionId}"]:checked`);
            if (!selectedAnswer) {
                alert('Please select an answer!');
                return;
            }
            const questionIndex = questions.findIndex(q => q.id === questionId);
            // Check if the question is already attempted
            const questionElement = document.getElementById('question-' + questionId);
            const submitButton = questionElement.querySelector('.sbmtbtn');
            const questiontableEle = questionElement.querySelector('.question-list');

            // Disable the submit button if the question has already been answered
            if (submitButton.disabled) {
                alert('You have already answered this question!');
                return;
            }

            const selectedAnswerValue = selectedAnswer.value;
            const correctAnswer = questions.find(q => q.id === questionId).correct_answer;
            const question = questions.find(q => q.id === questionId);
            const explanation = question.explanation; 
            document.getElementById('submit_question_loader').style.display = 'block';

            let currentTimeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
            questionTimes[questionId] = (questionTimes[questionId] || 0) + currentTimeSpent;

            questionStartTime = Date.now(); // Reset timer after submission

            // Submit the answer via API
            fetch(`{{ url('quiz/attempt') }}/${quizId}`, {
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
                if (data.success) {
                    document.getElementById('submit_question_loader').style.display = 'none';
                    // Disable the options and "submit" button after answering
                    questionElement.querySelectorAll('input').forEach(input => input.disabled = true);
                    // submitButton.disabled = true; 
                    submitButton.style.display = 'none';
                    let feedbackDiv = document.getElementById(`feedback-${questionId}`);
                    if (!feedbackDiv) {
                        feedbackDiv = document.createElement('div');
                        feedbackDiv.id = `feedback-${questionId}`;
                        feedbackDiv.className = 'feedback mt-4 text-gray-700';
                        questionElement.appendChild(feedbackDiv);
                    }
                    feedbackDiv.innerHTML = `
                    <div class="correct-ans">
                            <div>
                            <i class="fa-solid fa-check"></i>
                            <strong>Correct Answer:</strong> ${correctAnswer}</div>
                                <div>${questionTimes[questionId]} secs<br>
                                    Time Spent
                                </div>
                            </div>
                    <div class="Explanation-box"> 
                                <p class="explanation-tit"><strong>Explanation:</strong> ${explanation || 'No explanation available.'}</p>
                                </div>
                    `;
                    
                    

                    // Highlight the correct and incorrect answers
                    questiontableEle.querySelectorAll('li').forEach(li => {
                        const input = li.querySelector('input');
                        if (input.value === correctAnswer) {
                            li.classList.add('correct');
                        } else if (input.value === selectedAnswerValue) {
                            li.classList.add('incorrect');
                        }
                    });
                    updateSidebarIcon(questionIndex, selectedAnswerValue === correctAnswer);
                } else {
                    alert(data.message);
                }
            });
        }


        function submitQuiz() {
            // Submit the quiz via API to mark it as completed
            document.getElementById('submit_quiz_loader').style.display = 'block';
            fetch(`{{ url('quiz/end') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                    total_time: overallElapsedTime  // Send the total time spent on the quiz
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

        function updateSidebarIcon(questionIndex, isCorrect) {
            const sidebarItem = document.getElementById(`question-sidebar-item-${questionIndex}`);
            const existingIcon = sidebarItem.querySelector('span');
            
            // Remove existing icon if it exists
            if (existingIcon) {
                existingIcon.remove();
            }

            // Add new icon based on correctness
            const icon = document.createElement('span');
            icon.className = isCorrect ? 'text-green-500 ml-2' : 'text-red-500 ml-2';
            icon.innerHTML = isCorrect ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-xmark"></i>';
            sidebarItem.insertBefore(icon, sidebarItem.firstChild);
        }

        /** timer feature js code */
       

        // Start the overall quiz timer
        function startQuizTimer() {
            overallStartTime = Date.now() - overallElapsedTime * 1000; 
            overallTimerInterval = setInterval(updateOverallTimer, 1000); 
        }

        // Update the overall quiz timer every second
        function updateOverallTimer() {
            overallElapsedTime = Math.floor((Date.now() - overallStartTime) / 1000); 
            const hours = String(Math.floor(overallElapsedTime / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((overallElapsedTime % 3600) / 60)).padStart(2, '0');
            const seconds = String(overallElapsedTime % 60).padStart(2, '0');
            document.getElementById('quiz-timer').textContent = `${hours}:${minutes}:${seconds}`;
        }

        function stopQuizTimer() {
            overallElapsedTime = questionStartTime = 0;
        }

        function pauseTimer() {
            pausedTime = overallElapsedTime;
            savePausedTime(pausedTime);
        }

        function savePausedTime(pausedTime) {
            fetch(`{{ url('quiz/pause') }}/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                    paused_time: pausedTime
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
            pausedTime = overallElapsedTime; 
            
            savePausedTimer(pausedTime);
        });

        function savePausedTimer(pausedTime) {
            const url = `{{ url('quiz/pause') }}/${quizId}`;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const data = JSON.stringify({
                quiz_id: quizId,
                paused_time: pausedTime,
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

        startQuizTimer();

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

        const selection = window.getSelection();
            if (!selection.rangeCount || selection.isCollapsed) return;

            const range = selection.getRangeAt(0);

            //Find the closest `.question-list` that contains the selection
            const questionList = range.commonAncestorContainer.nodeType === 3
                ? range.commonAncestorContainer.parentElement.closest('.question-list')
                : range.commonAncestorContainer.closest('.question-list');

            if (!questionList) return; // Selection is not inside a question-list

            // Check if selection is already inside a strikethrough span
            const container = range.commonAncestorContainer.nodeType === 3
                ? range.commonAncestorContainer.parentElement
                : range.commonAncestorContainer;

            const existingStrike = container.closest('span[style*="text-decoration: line-through"]');

            if (existingStrike && selection.containsNode(existingStrike, true)) {
                // Remove existing strikethrough span
                const parent = existingStrike.parentNode;
                while (existingStrike.firstChild) {
                    parent.insertBefore(existingStrike.firstChild, existingStrike);
                }
                parent.removeChild(existingStrike);
                return;
            }

            // Apply clean strikethrough span

            // Clone and clean selection
            const fragment = range.cloneContents();
            const tempDiv = document.createElement('div');
            tempDiv.appendChild(fragment);

            tempDiv.querySelectorAll('span[style*="text-decoration: line-through"]').forEach(span => {
                const parent = span.parentNode;
                while (span.firstChild) {
                    parent.insertBefore(span.firstChild, span);
                }
                parent.removeChild(span);
            });

            // Wrap cleaned content
            const newSpan = document.createElement('span');
            newSpan.style.textDecoration = 'line-through';
            newSpan.innerHTML = tempDiv.innerHTML;

            // Replace selection with wrapped content
            range.deleteContents();
            range.insertNode(newSpan);

            // Optional: Reselect new content
            selection.removeAllRanges();
            const newRange = document.createRange();
            newRange.selectNodeContents(newSpan);
            selection.addRange(newRange);

           
            // let selection = window.getSelection();
            // if (selection.rangeCount) {
            //     let range = selection.getRangeAt(0);
            //     let parentElement = range.commonAncestorContainer.parentElement;
                
            //     // Check if the selected text is inside a question option
            //     if (parentElement.closest('.question-list')) {
            //         let span = document.createElement('span');
            //         span.style.textDecoration = 'line-through';
            //         range.surroundContents(span);
            //     } else {
            //         // alert("Strikethrough can only be applied to question options.");
            //     }
            // }
        }

        // Function to apply highlight
function applyHighlight(element) {
    const selection = window.getSelection();

    if (!selection || selection.isCollapsed || selection.rangeCount === 0) {
        return;
    }

    const range = selection.getRangeAt(0);

    // Detect if any part of the selection is already highlighted
    const highlightedSpansInRange = getHighlightedSpansInRange(range);

    if (highlightedSpansInRange.length > 0) {
        // 🔄 Unhighlight: unwrap any highlighted spans in the range
        highlightedSpansInRange.forEach(span => {
            unwrapSpan(span);
        });
    } else {
        // ✅ Highlight: apply highlight
        const span = document.createElement('span');
        span.classList.add('highlighted');

        try {
            range.surroundContents(span);
        } catch (e) {
            // Fallback for complex selection
            const contents = range.extractContents();
            span.appendChild(contents);
            range.insertNode(span);
        }
    }
}
// Find all real .highlighted spans intersecting the range
function getHighlightedSpansInRange(range) {
    const spans = [];
    const treeWalker = document.createTreeWalker(
        range.commonAncestorContainer,
        NodeFilter.SHOW_ELEMENT,
        {
            acceptNode: function (node) {
                if (
                    node.nodeType === 1 &&
                    node.tagName === "SPAN" &&
                    node.classList.contains("highlighted")
                ) {
                    const spanRange = document.createRange();
                    spanRange.selectNodeContents(node);

                    // Check if span overlaps with the selection range
                    return range.compareBoundaryPoints(Range.END_TO_START, spanRange) < 0 &&
                        range.compareBoundaryPoints(Range.START_TO_END, spanRange) > 0
                        ? NodeFilter.FILTER_ACCEPT
                        : NodeFilter.FILTER_REJECT;
                }
                return NodeFilter.FILTER_SKIP;
            },
        }
    );

    let currentNode;
    while ((currentNode = treeWalker.nextNode())) {
        spans.push(currentNode);
    }

    return spans;
}

// Unwraps a <span> by moving its contents to its parent and removing the span
function unwrapSpan(span) {
    const parent = span.parentNode;
    while (span.firstChild) {
        parent.insertBefore(span.firstChild, span);
    }
    parent.removeChild(span);
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
