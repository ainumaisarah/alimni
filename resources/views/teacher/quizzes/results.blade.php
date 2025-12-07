@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2 class="mb-4">Quiz Results: {{ $quiz->title }}</h2>

    @if($results->isEmpty())
        <p class="empty-message">No students have attempted this quiz yet.</p>
    @else
        @foreach($results as $result)
            <div class="info-card">
                <h3 class="mb-2">Student: {{ $result->student->name }}</h3>
                <p class="info-meta mb-3">Score: {{ $result->score }}/{{ $questions->count() }}</p>

                @foreach($questions as $question)
                    @php
                        $studentAnswer = strtoupper($result->answers[$question->id] ?? '');
                        $correctAnswer = strtoupper($question->correct_answer);
                    @endphp

                    <div class="info-card mb-2 p-3">
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
