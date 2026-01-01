@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('teacher.questions.index', $quiz->id) }}"
        class="h-8 w-8 inline-flex items-center justify-center p-2
                bg-gray-100 hover:bg-gray-200 rounded-lg
                text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="mb-4">Quiz Results Summary: {{ $quiz->title }}</h2>
    </div>

    @if($studentResults->isEmpty())
        <p class="empty-message">No students in this class.</p>
    @else
        <table class="w-full border text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Student</th>
                    <th class="p-2 border">Total Attempts</th>
                    <th class="p-2 border">Average Score</th>
                    <th class="p-2 border">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentResults as $res)
                    <tr>
                        <td class="p-2 border">{{ $res['student']->name }}</td>
                        <td class="p-2 border">{{ $res['totalAttempts'] }}</td>
                        <td class="p-2 border">
                            @if($res['totalAttempts'] > 0)
                                {{ round($res['attempts']->avg('score'), 2) }}%
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="p-2 border">
                            @if($res['totalAttempts'] > 0)
                                <select onchange="if(this.value) window.location.href=this.value">
                                    <option value="">Select Attempt</option>
                                    @foreach($res['attempts'] as $attempt)
                                        @php
                                            $attemptHasUngraded = false;

                                            foreach($quiz->questions as $question) {
                                                if ($question->question_type === 'short' && !isset($attempt->answers[$question->id.'_marks'])) {
                                                    $attemptHasUngraded = true;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <option value="{{ route('teacher.quizzes.review', [$quiz->id, $attempt->id]) }}">
                                            Attempt #{{ $attempt->attempt_number }} ({{ $attempt->score }}%)
                                            @if($attemptHasUngraded)
                                                - ⚠ Short Answer Grading
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
