@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Quiz Results: {{ $quiz->title }}</h2>

    @if($results->isEmpty())
        <p class="text-gray-500">No students have attempted this quiz yet.</p>
    @else
        @foreach($results as $result)
            <div class="mb-6 p-4 border rounded shadow">
                <h3 class="text-lg font-semibold">Student: {{ $result->student->name }}</h3>
                <p class="font-medium mb-4">Score: {{ $result->score }}/{{ $questions->count() }}</p>

                @foreach($questions as $question)
                    @php
                        $studentAnswer = strtoupper($result->answers[$question->id] ?? '');
                        $correctAnswer = strtoupper($question->correct_answer);
                    @endphp

                    <div class="mb-3 p-3 border rounded">
                        <p class="font-semibold mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                        <ul class="ml-4 mb-2 list-disc">
                            @foreach(['A','B','C','D'] as $opt)
                                @php
                                    $optionText = $question->{'option_'.strtolower($opt)};
                                    $isCorrect = $correctAnswer === $opt;
                                    $isSelected = $studentAnswer === $opt;
                                @endphp
                                <li>
                                    {{ $opt }}. {{ $optionText }}
                                    @if($isCorrect)
                                        — ✅ Correct Answer
                                    @endif
                                    @if($isSelected && !$isCorrect)
                                        — ❌ Student Selected
                                    @endif
                                    @if($isSelected && $isCorrect)
                                        — ✅ Student Selected
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>
@endsection
