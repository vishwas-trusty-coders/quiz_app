<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <!-- Dashboard Title -->
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight dashboard-title">
                {{ __('Performance Analytics') }}
            </h2>

            <x-user-dashboard-header-detail />
        </div>
    </x-slot>

    <div class="performance-analytics question-bank-analytics table-container-set ">
        <div id="charts">
            <!-- Display a single global legend -->
            <div class="global-legend">
                <span>
                    <div style="background-color: rgba(75, 192, 192, 0.8);"></div> Correct Answers
                </span>
                <span>
                    <div style="background-color: rgba(255, 99, 132, 0.8);"></div> Incorrect Answers
                </span>
                <span>
                    <div style="background-color: rgba(201, 203, 207, 0.8);"></div> Not Completed
                </span>
            </div>
            <div class="chart-main-container">
                @foreach ($questionBankStats as $questionBankId => $stats)
                    <!-- Wrap each canvas in a container with fixed dimensions -->
                    <a href="{{ $stats['question_bank_link'] }}" target="_blank">
                        <div class="chart-container">
                            <canvas id="chart-{{ $questionBankId }}"></canvas>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- <div class="responsive-table-wrapper">
            <table class="w-full border-collapse border border-gray-300 quiz-table">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Quiz Name</th>
                        <th class="border border-gray-300 px-4 py-2">Total Questions</th>
                        <th class="border border-gray-300 px-4 py-2">Correct Answers</th>
                        <th class="border border-gray-300 px-4 py-2">Incorrect Answers</th>
                        <th class="border border-gray-300 px-4 py-2">Not Attempted</th>
                        <th class="border border-gray-300 px-4 py-2">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($quizzesData))
                        @foreach($quizzesData as $data)
                            <tr class="quiz-row cursor-pointer" data-quiz-id="{{ $loop->iteration }}">
                                <td class="border border-gray-300 px-4 py-2">{{ $data['quiz_name'] }} <span><i class="fa-solid fa-angle-down"></i></span>
                                <div class="w-full empty_bar rounded-full h-2 mt-2">
                                    <div class="filled_bar h-2 rounded-full" style="width: {{ number_format(($data['correct_answers'] / $data['total_questions']) * 100, 2) }}%;"></div>
                                </div></td>
                                <td class="border border-gray-300 px-4 py-2">{{ $data['total_questions'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $data['correct_answers'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $data['incorrect_answers'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $data['not_attempted'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ number_format(($data['correct_answers'] / $data['total_questions']) * 100, 2) }}%</td>
                            </tr>

                            <tr id="subjects-row-{{ $loop->iteration }}" class="subjects-row hidden">
                                <td colspan="6" class="border border-gray-300 px-4 py-2 bg-gray-50">
                                    <table class="w-full border-collapse border border-gray-300 subject-table">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">Subject</th>
                                                <th class="border border-gray-300 px-4 py-2">Total Topics</th>
                                                <th class="border border-gray-300 px-4 py-2">Total Questions</th>
                                                <th class="border border-gray-300 px-4 py-2">Correct Answers</th>
                                                <th class="border border-gray-300 px-4 py-2">Incorrect Answers</th>
                                                <th class="border border-gray-300 px-4 py-2">Not Attempted</th>
                                                <th class="border border-gray-300 px-4 py-2">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['subjects'] as $subject)
                                                <tr class="subject-row cursor-pointer" data-subject-id="{{ $loop->parent->iteration }}-{{ $loop->iteration }}">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $subject['subject_name'] }} <span><i class="fa-solid fa-angle-down"></i></span>
                                                        <div class="w-full empty_bar rounded-full h-2 mt-2">
                                                            <div class="filled_bar h-2 rounded-full" style="width: {{ number_format(($subject['correct_answers'] / $subject['total_questions']) * 100, 2) }}%;"></div>
                                                        </div>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ count($subject['topics']) }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $subject['total_questions'] }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $subject['correct_answers'] }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $subject['incorrect_answers'] }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $subject['not_attempted'] }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ number_format(($subject['correct_answers'] / $subject['total_questions']) * 100, 2) }}%</td>
                                                </tr>

                                                <tr id="topics-row-{{ $loop->parent->iteration }}-{{ $loop->iteration }}" class="topics-row hidden">
                                                    <td colspan="7" class="border border-gray-300 px-4 py-2 bg-gray-50">
                                                        <table class="w-full border-collapse border border-gray-300 topic-table">
                                                            <thead class="bg-gray-200">
                                                                <tr>
                                                                    <th class="border border-gray-300 px-4 py-2">Topic Name</th>
                                                                    <th class="border border-gray-300 px-4 py-2">Total Questions</th>
                                                                    <th class="border border-gray-300 px-4 py-2">Correct Answers</th>
                                                                    <th class="border border-gray-300 px-4 py-2">Incorrect Answers</th>
                                                                    <th class="border border-gray-300 px-4 py-2">Not Attempted</th>
                                                                    <th class="border border-gray-300 px-4 py-2">Score</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($subject['topics'] as $topic)
                                                                    <tr>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ $topic['topic_name'] }}
                                                                            <div class="w-full empty_bar rounded-full h-2 mt-2">
                                                                            <div class="filled_bar h-2 rounded-full" style="width: {{ number_format(($topic['correct_answers'] / $topic['total_questions']) * 100, 2) }}%;"></div>
                                                                        </div></td>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ $topic['total_questions'] }}</td>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ $topic['correct_answers'] }}</td>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ $topic['incorrect_answers'] }}</td>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ $topic['not_attempted'] }}</td>
                                                                        <td class="border border-gray-300 px-4 py-2">{{ number_format(($topic['correct_answers'] / $topic['total_questions']) * 100, 2) }}%</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statsData = @json($questionBankStats);

        Object.keys(statsData).forEach(questionBankId => {
            const stats = statsData[questionBankId];
            const ctx = document.getElementById(`chart-${questionBankId}`).getContext('2d');

            // Calculate total responses
            const total = stats.correct_answers + stats.incorrect_answers + stats.not_completed;

            // Data for the chart
            const dataValues = [stats.correct_answers, stats.incorrect_answers, stats.not_completed];
            const labels = ['Correct Answers', 'Incorrect Answers', 'Not Completed'];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: stats.question_bank_name,
                        data: dataValues,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.8)', // Green
                            'rgba(255, 99, 132, 0.8)', // Red
                            'rgba(201, 203, 207, 0.8)'  // Gray
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)', // Green
                            'rgba(255, 99, 132, 1)', // Red
                            'rgba(201, 203, 207, 1)'  // Gray
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false, // Disable the legend in the chart
                        },
                        title: {
                            display: true,
                            position: 'bottom',
                            text: stats.question_bank_name,
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const dataset = tooltipItem.dataset;
                                    const index = tooltipItem.dataIndex;
                                    const value = dataset.data[index]; // Get the number of responses
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0; // Calculate percentage
                                    return `${labels[index]}: ${value} (${percentage}%)`; // Format tooltip text
                                }
                            }
                        }
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
        // Toggle subjects table on quiz name click
        document.querySelectorAll('.quiz-row').forEach(row => {
            row.addEventListener('click', function () {
                const quizId = this.dataset.quizId;
                const subjectsRow = document.getElementById(`subjects-row-${quizId}`);
                subjectsRow.classList.toggle('hidden');
            });
        });

        // Toggle topics table on subject name click
        document.querySelectorAll('.subject-row').forEach(row => {
            row.addEventListener('click', function () {
                const subjectId = this.dataset.subjectId;
                const topicsRow = document.getElementById(`topics-row-${subjectId}`);
                topicsRow.classList.toggle('hidden');
            });
        });
    });

    </script>
    <style>
    .hidden {
        display: none;
    }
    .cursor-pointer {
        cursor: pointer;
    }

    </style>
</x-app-layout>