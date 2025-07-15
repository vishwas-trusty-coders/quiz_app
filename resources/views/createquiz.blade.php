<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Quiz') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>



    <div id="Create-Quiz-form">
        <div class="row">
            <div class="container">
                <h2>{{ $questionBank->name }}</h2>
                <form id="quiz-form" method="POST">
                    @csrf
                    <input type="hidden" name="question_bank_id" value="{{ $questionBank->id }}">
                    <!-- Quiz Name -->
                    <label for="quiz-name" class="quiz-name">Quiz Name: {{-- <i class="fa-solid fa-circle-info"></i> --}} </label>
                    <input type="text" id="quiz-name" class="input-quiz" name="name" placeholder="My Anatomy Quiz" value="{{ $questionBank->name }} Quiz  {{ now()->format('d-M-Y') }}" required>

                    <!-- Test Mode -->
                    <div class="column-box-quiz">
                        <p class="quiz-title">Test Mode {{-- <i class="fa-solid fa-circle-info"></i> --}}</p>
                        <div class="toggle-group">
                            <!-- Hidden input to capture the "off" state when unchecked -->
                            <input type="hidden" name="tutor_mode" value="off">
                            <label>
                                <input type="checkbox" class="hidden-checkbox" name="tutor_mode" id="tutor-mode" checked>
                                <span class="toggle-btn"></span> Tutor
                            </label>

                            <!-- Hidden input to capture the "off" state when unchecked -->
                            <input type="hidden" name="timed_mode" value="off">
                            <label>
                                <input type="checkbox" class="hidden-checkbox" name="timed_mode" id="timed-mode">
                                <span class="toggle-btn"></span> Timed
                            </label>
                        </div>
                    </div>


                    <input type="hidden" name="tags" value="{{ json_encode($tags) }}">
                    <!-- Select Subject -->
                    <div class="column-box-quiz">
                        <div class="select-box-set">
                            <p class="quiz-title">
                                <input type="checkbox" id="select-all-subjects"> Select All Subjects {{-- <i class="fa-solid fa-circle-info"></i> --}}
                            </p>
                            <div class="checkbox-group">
                                <!-- @foreach($subjects as $subjectId => $subjectData)
                                <label><input type="checkbox" name="subject[]" value="{{ $subjectId }}" class="subject-checkbox"> {{ $subjectData['name'] }} ({{ $subjectData['count'] }})</label>
                                @endforeach -->

                                @php
                                $sortedSubjects = collect($subjects)->sortBy('name');
                                @endphp

                                @foreach($sortedSubjects as $subjectId => $subjectData)
                                <label class=" @if($subjectData['count']==0) topic-disabled @endif">
                                    <input type="checkbox" name="subject[]" value="{{ $subjectId }}" class="subject-checkbox" @if($subjectData['count']==0) disabled @endif>
                                    {{ $subjectData['name'] }} ({{ $subjectData['count'] }})
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Select Topic -->
                    <div class="column-box-quiz topic-col">
                        <div class="select-box-set">
                            <p class="quiz-title">
                                <input type="checkbox" id="select-all-topics"> Select All Topics {{-- <i class="fa-solid fa-circle-info"></i> --}}
                            </p>
                            <div class="checkbox-group" id="topic-container">
                                @foreach($topics as $topic)
                                <label class="topic-label-disabled @foreach($topic['id'] as $id) topic-label-{{$id}} @endforeach ">
                                    <input type="checkbox" name="topic[]" value="@if(is_array($topic['id'])) {{ implode(',',$topic['id']) }} @else {{$topic['id']}} @endif" class="topic-disabled @foreach($topic['id'] as $id) topic-{{$id}}@endforeach" disabled> {{$topic['name']}} (<span class="question-counter @if(is_array($topic['id']))  @foreach($topic['id'] as $topic) topic-question-count-{{$topic}} @endforeach @else topic-question-count-{{$topic['id']}} @endif">0</span>)
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Question Status -->
                    <div class="column-box-quiz">
                        <p class="quiz-title">Question Status {{-- <i class="fa-solid fa-circle-info"></i> --}} </p>
                        <div class="radio-group">
                            <label><input type="checkbox" name="question_status[]" value="all"> All Questions</label>
                            <label><input type="checkbox" name="question_status[]" value="new"> New</label>
                            <label><input type="checkbox" name="question_status[]" value="incorrect"> Incorrect</label>
                            <label><input type="checkbox" name="question_status[]" value="correct"> Correct</label>
                            <label><input type="checkbox" name="question_status[]" value="flagged"> Flagged</label>
                        </div>
                    </div>

                    <!-- Question Quantity -->
                    <label for="quantity" class="Question-title">Question Quantity :</label> <span class="total_que_count"></span><br>
                    <input type="number" id="quantity" name="question_quantity" class="input-quiz-quantity" min="1" value=""> Questions

                    <!-- Submit Button -->
                    <button type="submit" class="Generate-btn">Generate Quiz</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Success Modal Popup -->
    <div id="success-popup" style="display: none; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="background: white; margin: 20% auto; padding: 20px; text-align: center; border-radius: 8px;">
            <p id="popup-message"></p>
            <div class="popup_buttons">
                <button id="close_popup" style="background-color:rgb(236, 26, 11); color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">
                    Close
                </button>
                <!-- Dynamically added buttons -->

                <button id="quiz_start" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; display: none;">
                    Start Quiz
                </button>
                <button id="go_to_dashboard" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; display: none;">
                    Go To Dashboard
                </button>
            </div>
        </div>
    </div>



    <script>
        // Select the checkbox by its ID (replace 'myCheckbox' with your checkbox's ID)
        $(document).on('change', '.topic', function() {
            // $("#captureAudio").prop('checked', false);
            $('input[name="question_status[]"]').prop('checked', false);
            $('.total_que_count').text('');
        });

        function getDataAjaxCall() {

            $('input[name="question_status[]"]').prop('checked', false);
            $('.total_que_count').text('');

            $('.question-counter').text(0);
            $('.topic').attr('disabled', true);

            $('.topic-label').addClass('topic-label-disabled');
            $('.topic-label-disabled').removeClass('topic-label');

            // $('.topic').prop('checked', false);
            formData = $('#quiz-form').serialize();
            var url = '{{ route("get.topics.questions.count") }}';
            // var formData={'subject':$(this).val()}
            $.ajax({
                url: url, // Adjust to match your route
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
                },
                success: function(response) {
                    result = JSON.parse(response.data);
                    $.each(result, function(index, element) {
                       
                        if (parseInt(element.questions_count) != 0) {
                            if($('.topic-' + element.id).is(':checked')) {
                                $('.topic-' + element.id).addClass('previously-checked');
                            }

                            $('.topic-label-' + element.id).removeClass('topic-label-disabled');
                            $('.topic-label-' + element.id).addClass('topic-label');
                            $('.topic-' + element.id).removeClass('topic-disabled');
                            $('.topic-' + element.id).addClass('topic');
                            $('.topic-' + element.id).attr('disabled', false);
                            $('.topic-question-count-' + element.id).text(parseInt($('.topic-question-count-' + element.id).text()) + parseInt(element.questions_count));
                        }else{
                            
                        }
                        //console.log("Index: " + index + ", ID: " + element.id + ", Name: " + element.name);

                    });

                    $('.topic').prop('checked', false);
                    $('.previously-checked').prop('checked', true);
                    $('.topic').removeClass('previously-checked');

                },
                error: function(xhr) {
                    $('.total_que_count').text('');
                    console.error(xhr.responseText);
                    // Handle error
                }
            });
        }
        // Select All Subjects
        document.getElementById('select-all-subjects').addEventListener('change', function() {
            $('input[name="question_status[]"]').prop('checked', false);
            $('.total_que_count').text('');
            let checkboxes = document.querySelectorAll('input[name="subject[]"]:enabled');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;

            });
            //jQuery('.topic-col').show();
            getDataAjaxCall();
        });

        // Select All Topics
        document.getElementById('select-all-topics').addEventListener('change', function() {

            $('input[name="question_status[]"]').prop('checked', false);
            $('.total_que_count').text('');

            let checkboxes = document.querySelectorAll('input[name="topic[]"]:enabled');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Handle Subject Selection Change
        document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {


                if (!this.checked) {
                    document.getElementById('select-all-subjects').checked = false;
                }

                // Check if all subject checkboxes are selected and update "Select All Subjects"
                updateSelectAllSubjects();
                //jQuery('.topic-col').show();
            });
        });




        function updateSelectAllSubjects() {
            let allSubjects = document.querySelectorAll('input[name="subject[]"]');
            let selectAllCheckbox = document.getElementById('select-all-subjects');
            let allChecked = Array.from(allSubjects).every(checkbox => checkbox.checked);

            // Check "Select All Subjects" if all individual checkboxes are selected
            selectAllCheckbox.checked = allChecked;
        }

        document.getElementById('topic-container').addEventListener('change', function(event) {

            if (event.target && event.target.classList.contains('topic-checkbox')) {
                // If any topic is unchecked, uncheck "Select All Topics"
                if (!event.target.checked) {
                    document.getElementById('select-all-topics').checked = false;
                }

                // Check if all topic checkboxes are selected and update "Select All Topics"
                updateSelectAllTopics();
            }
        });

        // Function to handle "Select All Topics" checkbox state
        function updateSelectAllTopics() {
            let allTopics = document.querySelectorAll('input[name="topic[]"]');
            let selectAllCheckbox = document.getElementById('select-all-topics');


            // Check if all topics are selected
            let allChecked = Array.from(allTopics).every(checkbox => checkbox.checked);

            // If all topics are selected, check the "Select All Topics" checkbox
            if (allChecked) {
                selectAllCheckbox.checked = true;
            } else {
                // If not all topics are selected, uncheck the "Select All Topics" checkbox
                selectAllCheckbox.checked = false;
            }
        }

        $(document).ready(function() {




            $('#tutor-mode').on('change', function() {
                if ($(this).prop('checked')) {
                    $('#timed-mode').prop('checked', false); // Uncheck Timed mode if Tutor mode is checked
                } else {
                    $('#timed-mode').prop('checked', true);
                }
            });

            // Handle Timed Mode toggle
            $('#timed-mode').on('change', function() {
                if ($(this).prop('checked')) {
                    $('#tutor-mode').prop('checked', false); // Uncheck Tutor mode if Timed mode is checked
                } else {
                    $('#tutor-mode').prop('checked', true);
                }
            });
            // Listen for the form submission event
            $('#quiz-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                const checkboxes = document.querySelectorAll('input[name="question_status[]"]');
                const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                if (!isChecked) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please select at least one question status.');
                    return false;
                }
                var formData = $(this).serialize(); // Get all the form data

                // Define the URL to submit the form data (make sure this points to your store route)
                var url = '{{ route("quiz.store") }}';

                // Perform the AJAX request
                $.ajax({
                    url: url, // Use the URL directly for the AJAX request
                    method: 'POST',
                    data: formData, // Send the serialized form data
                    success: function(response) {
                        // console.log(response);

                        // Clear previous popup content
                        $('#popup-message').text('');
                        $('#quiz_start').hide();
                        $('#go_to_dashboard').hide();
                        $('#close_popup').show();

                        if (response.success === true) {
                            // Show the success message inside the popup
                            $('#popup-message').text(response.message || 'Quiz created successfully!');

                            // Show the additional buttons based on success
                            $('#quiz_start').show();
                            $('#go_to_dashboard').show();
                            $('#close_popup').hide();

                            // Actions for the "Start Quiz" button
                            $('#quiz_start').on('click', function() {
                                $('#success-popup').fadeOut();
                                if (response.mode === 'on') {
                                    window.location.href = '/timedquiz/start/' + response.quiz_id;
                                } else {
                                    window.location.href = '/quiz/start/' + response.quiz_id;
                                }
                            });

                            // Actions for the "Go to Dashboard" button
                            $('#go_to_dashboard').on('click', function() {
                                $('#success-popup').fadeOut();
                                window.location.href = '/dashboard'; // Replace with the actual redirect URL you need
                            });
                        } else {
                            // Display error message inside the popup
                            $('#popup-message').text(response.errors || 'An error occurred.');
                            $('#close_popup').on('click', function() {
                                $('#success-popup').fadeOut();
                            });
                        }

                        // Show the success popup
                        $('#success-popup').fadeIn();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error occurred:', error);

                        // Clear previous popup content and display a general error message
                        $('#popup-message').text('An error occurred while processing your request.');
                        $('#quiz_start').hide();
                        $('#go_to_dashboard').hide();
                        $('#close_popup').show();

                        // Show the error popup
                        $('#success-popup').fadeIn();

                        // Handle close button
                        $('#close_popup').on('click', function() {
                            $('#success-popup').fadeOut();
                        });
                    }
                });
            });
        });

        $(document).on('change', 'input[name="subject[]"]', function() {
            getDataAjaxCall();
        });

        $(document).on('change', 'input[name="question_status[]"]', function() {
            // Serialize form data
            if ($(this).val() === 'all') {
                // If "All" is checked, uncheck all others
                if ($(this).is(':checked')) {
                    $('input[name="question_status[]"]').not(this).prop('checked', false);
                }
            } else {
                // If any other status is checked, uncheck "All"
                $('input[name="question_status[]"][value="all"]').prop('checked', false);
            }
            const formData = $('#quiz-form').serialize();
            var url = '{{ route("question_count") }}';
            // Make AJAX request
            $.ajax({
                url: url, // Adjust to match your route
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
                },
                success: function(response) {
                    const questionQuantityField = $('input[name="question_quantity"]');
                    if (response.count > 0) {
                        questionQuantityField.attr('max', response.count);
                        $('span.total_que_count').html('Max allowed (' + response.count + ')');
                    } else {
                        questionQuantityField.attr('max', 0);
                        $('span.total_que_count').html('No question found for status (' + response.status + ')');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // Handle error
                }
            });
        });
    </script>

</x-app-layout>