<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Question Bank') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

    <div class="">
        <div class="">
            <div class="">
                <div class="">
                    <!--add html-->
                    @if(!empty($purchases))
                    <div class="Purchase_button">
                                <button onclick="window.open('{{ route('questionbank_package') }}')">Purchase a question bank</button>
</div>
                                @endif
                    <div id="bank-card">
                        <div class="row">
                            <div class="container">
                                
                                @forelse ($purchases as $purchase)
                                    <!-- Card 1 -->
                                    <div class="card">
                                        <div class="icon">
                                            <i class="fas fa-clipboard-question"></i>
                                        </div>
                                        <h3>{{ $purchase->question_bank_name }}</h3>
                                        <p>{{ $purchase->questionBank->description }}</p>
                                        <a href="{{ route('quiz.create', ['id' => $purchase->question_bank_id]) }}" class="create-quiz">
                                            CREATE QUIZ
                                        </a>
                                    </div>
                                @empty
                                    <div class="no_purchase_found"><h3>Question bank not puchased yet. <a href="{{ route('questionbank_package') }}">Click here</a> to purchase.</h3></div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>