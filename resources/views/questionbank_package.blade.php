<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Purchase package') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>
    <div class="container available-title">
        <h2>Available Question Banks</h2>

        <!-- Success and Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success" style="color:#149606;"><h3>{{ session('success') }}</h3></div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="color:#f50505;"><h3>{{ session('error') }}</h3></div>
        @endif

        <!-- Question Bank Packages List -->
        <div class="question_bank_list">
            @foreach($questionBanks as $bank)
                <div class="listcard">
                    <div class="card-header">
                        <h3 class="card-title">{{ $bank->name }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Subject(s):</strong> {{ $bank->subject_names }}</p>
                        <!-- <p><strong>Topic:</strong> {{ $bank->topic_names }}</p> -->
                        <p><strong>Credits Required:</strong> {{ $bank->credit }} credits</p>
                        <p><strong>Description:</strong> {{ $bank->description }}</p>

                        <!-- Purchase Button -->
                        <form action="{{ route('purchase.question', $bank->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Purchase</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>