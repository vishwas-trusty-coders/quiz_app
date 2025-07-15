<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight dashboard-title">
                {{ __('Dashboard') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

   


    <!--Welcome sec --> 
    <div id="welcome-sec">
        <div class="row">
            <div class="container">
                <div class="column-welcome">
                    @if(!empty(Auth::user()->profile_image))
                        <img class="profile-img" src="{{asset('storage/'. Auth::user()->profile_image) }}" alt="profile image">
                    @else
                        <img class="profile-img" src="../img/avatar.jpg" alt="profile image">
                    @endif
                </div>
                @php 
                    $user = Auth::user();
                    $joined_date = date('F d, Y', strtotime($user->created_at)); 
                @endphp
                <div class="">
                    <div class="date-profile">Joined on {{ $joined_date }}</div>
                    <div class="title-profile"><h2>Welcome Back, {{ $user->first_name }} {{ $user->last_name }}!</h2></div>
                    <div class="credits">{{ $user->credits }} credits</div>
                    <div class="credit-btn"><a href="{{ route('buy_credit') }}">BUY CREDITS</a></div>
                </div>
            </div>
        </div>
    </div>


    <!---My Purchases -->
    <div class="toggle_puchase_data">
        <div class="container">
            <h2>Purchase Section</h2>
            <button id="toggleButton" class="btn btn-link text-decoration-none">
                <i id="toggleIcon" class="fa-solid fa-chevron-down"></i>
            </button>
        </div>
        
    </div>
    <div id="my-purchases" class="collapsible">
        <div class="row">
            <div class="container">
                <div class="purchases-box">
                    <div class="header">
                        <h2>My Purchases</h2>
                        <a href="{{ route('questionbank') }}" class="view-all">View All</a>
                    </div>
                    <ul class="purchase-list">
                        @forelse ($purchases as $purchase)
                            <li class="purchase-item d-flex justify-content-between align-items-center border-bottom py-3">
                                <!-- Purchase Info -->
                                <div class="purchase-info d-flex">
                                    <i class="fa-solid fa-clipboard-list paper-list-icon me-3 fs-4 text-danger"></i>
                                    <div class="purchase-text">
                                        <h3 class="mb-1">{{ $purchase->question_bank_name }}</h3>
                                        {{-- <p class="text-muted mb-0">
                                        {{ $purchase->subject_names }}, {{ $purchase->topic_names }}
                                        </p> --}}
                                    </div>

                                </div>
                                <!-- Create Quiz Button -->
                                <div>
                                    <a href="{{ route('quiz.create', ['id' => $purchase->question_bank_id]) }}" class="create-quiz">
                                        Create Quiz
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-3 text-muted">No purchases available.</li>
                        @endforelse
                    </ul>
    
                </div>
                    <div class="purchase-history">
                        <h2>Purchase History <a href="#" class="view-all">View All</a></h2>
                        @foreach($orders as $order)
                        @php 
                            $purchasedDate = date('M d, Y', strtotime($order->created_at));
                            $purchasedDay = date('D', strtotime($order->created_at));
                            $purchasedTime = date('h:i A', strtotime($order->created_at));
                        @endphp
                            <div class="history-item">
                                <div class="date-circle">
                                    {{ $purchasedDate }}<br>
                                    {{ $purchasedDay }}
                                </div>
                                <div class="history-details">
                                    <span class="credits">Bought {{ $order->credits }} Credits</span>
                                    <span>{{ $purchasedTime }}</span>
                                </div>
                                <!-- <div class="more-options">&#8942;</div> -->
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
    
    <!-- Quiz list -->
    <div id="progress-sec">
        <h2>My Quizzes</h2>
        <div class="quiz-container">
        @forelse ($quizzesData as $quizzData)
            <div class="quiz-card">
                <div class="status_div">
                    <div class="status {{$quizzData['status']}}">{{ ucwords(str_replace('_', ' ', $quizzData['status'])) }}</div>
                    @if($quizzData['status'] == 'completed')
                    <div class="completed_date">{{ $quizzData['updated_at'] }}</div>
                    @endif
                </div>
                <div class="progress-ring" style="--progress: {{ $quizzData['score'] }};">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle class="bg" cx="60" cy="60" r="50" style="stroke-width: 10;"></circle>
                        <circle class="fg" cx="60" cy="60" r="50" style="stroke-width: 10; stroke-dasharray: 314.16; stroke-dashoffset: 78.54;"></circle>
                    </svg>
                    <div class="percentage">{{ $quizzData['score'] }}%</div>
                </div>
                <div class="title-heading">
                    <h3>{!! Illuminate\Support\Str::limit($quizzData['name'], 20, ' ...') !!}</h3>
                </div>
                {{-- <p class="quiz-card-description">
                @foreach($quizzData['subjects'] as $index => $subject)
                    {{ $subject['name'] }} ({{ $subject['question_count'] }}),
                @endforeach
                @foreach($quizzData['topics'] as $index => $topics)
                    {{ $topics['name'] }} ({{ $topics['question_count'] }}){{ $index < count($quizzData['topics']) - 1 ? ',' : '' }}
                @endforeach
                </p> --}}
                @if($quizzData['status'] == 'not_started')
                    <button onclick="startQuiz({{ $quizzData['id'] }}, '{{ $quizzData['mode'] }}')" class="btn btn-warning">Start Quiz</button>
                    @elseif($quizzData['status'] == 'in_progress')
                    <button onclick="resumeQuiz({{ $quizzData['id'] }}, '{{ $quizzData['mode'] }}')" class="btn btn-warning">Resume Quiz</button>
                    @elseif($quizzData['status'] == 'completed')
                    <button onclick="window.open('{{ route('quiz.result', ['quizId' => $quizzData['id']]) }}', '_blank')" class="btn btn-warning">Review Quiz</button>
                    @endif

                    <a href="{{route('quiz.delete',$quizzData['id']) }}" class="quiz-card-a btn btn-warning px-2" onclick="return confirm('Are you sure you want to delete this quiz?');"><i class="fa-regular fa-trash-can"></i></a>
                
            </div>

        @empty
            <div class="no_quiz_found"><h3>You have not created any quiz yet.</h3></div>
        @endforelse
        </div>
    </div>

    <script>
        function startQuiz(quizId, mode) {
            if(mode === 'tutor'){
                window.location.href = "{{ url('quiz/start') }}/" + quizId;  
            }else{
                window.location.href = "{{ url('timedquiz/start') }}/" + quizId;  
            }
        }
        
        function deleteQuiz(quizId) {
                window.location.href = "{{ url('quiz/delete') }}/" + quizId;  
        }

        function resumeQuiz(quizId, mode) {
            if(mode === 'tutor'){
                window.location.href = "{{ url('quiz/resume') }}/" + quizId; 
            }else{
                window.location.href = "{{ url('timedquiz/resume') }}/" + quizId; 
            }
        }

        document.getElementById("toggleButton").addEventListener("click", function () {
            let purchasesBox = document.getElementById("my-purchases");
            let icon = document.getElementById("toggleIcon");

            if (purchasesBox.classList.contains("collapsed")) {
                purchasesBox.style.display = 'block'; // Expand
                icon.classList.replace("fa-chevron-right", "fa-chevron-down");
            } else {
                purchasesBox.style.display = "none"; // Collapse
                icon.classList.replace("fa-chevron-down", "fa-chevron-right");
            }
            purchasesBox.classList.toggle("collapsed");
        });

        // Ensure it starts visible
        window.addEventListener("load", function () {
            let purchasesBox = document.getElementById("my-purchases");
            purchasesBox.style.maxHeight = purchasesBox.scrollHeight + "px"; // Ensure it starts expanded
        });

    </script>
</x-app-layout>
