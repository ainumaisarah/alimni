@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <div class="page-container p-6">

    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
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
        <h2 class="mb-4 text-2xl font-semibold">
        Review: {{ $quiz->title }} (Student: {{ $result->student->name }}, Attempt #{{ $result->attempt_number }})
        </h2>
    </div>


    <p class="mb-4 font-semibold">Score: {{ $result->score }}%</p>

    <div class="space-y-4">
        @foreach($questions as $question)
            @php
                $studentAnswer = $result->answers[$question->id] ?? 'Not answered';
                $isCorrect = ($studentAnswer == $question->correct_answer);
            @endphp

            <div class="info-card p-3 border rounded">
                <p class="font-semibold mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                <p><strong>Student Answer:</strong> {{ $studentAnswer }}</p>

                @if($quiz->show_answers)
                    <p class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                        <strong>Correct Answer:</strong> {{ $question->correct_answer }}
                    </p>
                    <p>
                        <strong>Status:</strong>
                        @if($isCorrect)
                            <span class="text-green-600">Correct</span>
                        @else
                            <span class="text-red-600">Incorrect</span>
                        @endif
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
