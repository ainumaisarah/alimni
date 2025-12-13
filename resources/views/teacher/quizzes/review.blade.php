@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <h2 class="mb-4 text-2xl font-semibold">
        Review: {{ $quiz->title }} (Student: {{ $result->student->name }}, Attempt #{{ $result->attempt_number }})
    </h2>

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
