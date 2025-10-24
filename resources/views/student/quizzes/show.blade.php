@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h2>

    @if($result)
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 font-semibold">
            You already submitted this quiz. Score: {{ $result->score }}/{{ $quiz->questions->count() }}
        </div>
    @endif

    <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
        @csrf

        @foreach($questions as $question)
        <div class="mb-4 p-4 border rounded shadow-sm">
            <p class="font-semibold text-lg mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</p>

            @php
                $prevAnswer = strtoupper($result->answers[$question->id] ?? '');
                $correctAnswer = strtoupper($question->correct_answer);
            @endphp

            @foreach(['a','b','c','d'] as $option)
                @php
                    $optionText = $question->{'option_'.$option};
                    $isSelected = $prevAnswer == strtoupper($option);
                    $isCorrect = $correctAnswer == strtoupper($option);
                    $bgClass = '';
                    $borderClass = '';
                    $icon = '';

                    if($result) {
                        if($isCorrect) {
                            $icon = '✅';
                        } elseif($isSelected && !$isCorrect) {
                            $icon = '❌';
                        }
                    }
                @endphp

                <div class="p-3 rounded mb-2 flex items-center gap-2 {{ $bgClass }} {{ $borderClass }}">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                        {{ $isSelected ? 'checked' : '' }}
                        @if($result) disabled @endif
                    >
                    <span class="font-medium">{{ strtoupper($option) }}. {{ $optionText }} {{ $icon }}</span>
                </div>
            @endforeach
        </div>
        @endforeach

        @if(!$result)
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Submit Quiz
            </button>
        @endif
    </form>
</div>
@endsection
