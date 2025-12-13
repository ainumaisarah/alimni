@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="mb-4">
        Review: {{ $quiz->title }} (Attempt #{{ $result->attempt_number }})
    </h2>

    @php
        $totalQuestions = $questions->count();
        $correctCount = 0;

        // Count correct answers (used for display)
        foreach($questions as $question){
            $studentAnswer = $answers[$question->id] ?? null;
            if($studentAnswer !== null && $studentAnswer == $question->correct_answer){
                $correctCount++;
            }
        }
    @endphp

    <!-- Score & Correct/Total -->
    <p class="text-xl font-semibold mb-2">Score: {{ $result->score }}%</p>
    <p class="mb-6">Correct: {{ $correctCount }} / {{ $totalQuestions }}</p>

    <div class="space-y-4">
        @foreach($questions as $question)
            @php
                $studentAnswer = $answers[$question->id] ?? 'Not answered';
                $isCorrect = ($studentAnswer == $question->correct_answer);
            @endphp

            <div class="info-card">
                <!-- Question -->
                <p class="font-semibold mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                <!-- Student Answer -->
                <p><strong>Your Answer:</strong> {{ $studentAnswer }}</p>

                <!-- Correct Answer & Status (only if teacher allows) -->
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
