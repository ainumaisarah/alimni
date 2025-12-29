@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.quizzes', $quiz->classroom_id) }}"
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
        <h2 class="font-semibold text-2xl">{{ $quiz->title }}</h2>
    </div>

    <p class="mb-4">{{ $quiz->description }}</p>

    @if($attemptsCount == 0)
        <p>Please attempt the quiz.</p>
        <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-primary">Attempt Quiz</a>
    @else
        <table class="w-full text-left border mb-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Attempt</th>
                    <th class="p-2 border">Score</th>
                    <th class="p-2 border">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $attempt)
                    @php
                        $attemptHasUngraded = false;

                        foreach($attempt->answers as $questionId => $answerValue) {
                            $question = $questions->firstWhere('id', $questionId);
                            if($question && $question->question_type === 'short') {
                                // If marks not yet given for this short answer
                                if(!isset($attempt->answers[$questionId.'_marks'])) {
                                    $attemptHasUngraded = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <tr>
                        <td class="p-2 border">{{ $attempt->attempt_number }}</td>
                        <td class="p-2 border">
                            {{ $attempt->score }}%
                            @if($attemptHasUngraded)
                                <span class="text-orange-600 font-semibold">(not final)</span>
                            @endif
                        </td>
                        <td class="p-2 border">
                            <a href="{{ route('student.quizzes.review', [$quiz->id, $attempt->id]) }}" class="text-blue-600 underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($attemptsCount < $maxAttempts)
            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-primary mt-2 inline-block">Attempt Again</a>
            <p class="mt-1 text-sm text-gray-500">
                You can attempt {{ $maxAttempts - $attemptsCount }} more time(s).
            </p>
        @else
            <p class="mt-2 text-red-600 font-semibold">
                You have reached the maximum attempts ({{ $maxAttempts }}).
            </p>
        @endif
    @endif
</div>
@endsection
