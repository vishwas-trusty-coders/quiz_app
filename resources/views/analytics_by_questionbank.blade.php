<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight dashboard-title">
                {{ __('Performance Analytics By Subjects') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

    <div class="performance-analytics table-container-set">
    <div class="responsive-table-wrapper">
            <table class="w-full border-collapse quiz-table">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 w-100">Name</th>
                        <th class="px-4 py-2 w-48">Usage</th>
                        <th class="px-4 py-2 w-48">Correct Q</th>
                        <th class="px-4 py-2 w-48">Incorrect Q</th>
                        <th class="px-4 py-2 w-48">Omitted Q</th>
                        <th class="px-4 py-2 w-48">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($subjectStats))
                        @foreach($subjectStats as $data)
                        @php 
                        if($data['usage'] >0){
                            $usage = $data['usage'];
                        }else{
                            $usage = $data['total_questions'];
                        }
                        if($usage >0){
                            $total_percent = number_format(($data['correct_answers'] / $usage) * 100, 2);
                        }else{
                            $total_percent = 0;
                        }
                        @endphp
                            <tr class="quiz-row cursor-pointer" data-quiz-id="{{ $loop->iteration }}" data-href="{{ $data['subject_link'] }}">
                                <td class="px-4 py-2 w-100">{{ $data['subject_name'] }}
                                    <div class="w-full empty_bar rounded-full h-2 mt-2">
                                        <div class="filled_bar h-2 rounded-full" style="width: {{ $total_percent }}%;"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 w-48">{{ $data['usage']}}/{{ $data['total_questions'] }}</td>
                                <td class="px-4 py-2 w-48 correct_col">{{ $data['correct_answers'] }}</td>
                                <td class="px-4 py-2 w-48 incorrect_col">{{ $data['incorrect_answers'] }}</td>
                                <td class="px-4 py-2 w-48 ommitted_col">{{ $data['not_attempted'] }}</td>
                                <td class="px-4 py-2 w-48">{{ $total_percent }}%</td>
                            
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".quiz-row").forEach(function (row) {
                row.addEventListener("click", function (event) {
                    // Prevent row click if the user clicks on a link
                    if (!event.target.closest("a")) {
                        window.open(row.getAttribute("data-href"), "_blank");
                    }
                });
            });
        });
    </script>
</x-app-layout>