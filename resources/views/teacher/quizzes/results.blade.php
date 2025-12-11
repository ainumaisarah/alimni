@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2 class="mb-4">Quiz Results: {{ $quiz->title }}</h2>

    @if($results->isEmpty())
        <p class="empty-message">No students have attempted this quiz yet.</p>
    @else
        @foreach($results as $result)
            <div class="info-card mb-4 p-4">
                <h3 class="mb-2 font-semibold">Student: {{ $result->student->name }}</h3>
                <p class="info-meta mb-3">Score: {{ $result->score }}/{{ $questions->count() }}</p>

                @foreach($questions as $question)
                    @php
                        $studentAnswer = $result->answers[$question->id] ?? '';
                        $correctAnswer = $question->correct_answer;
                        $revealAnswers = $quiz->show_answers; // teacher toggle
                    @endphp

                    <div class="info-card mb-2 p-3">
                        <p class="font-semibold mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                        @if($question->question_type === 'mcq')
                            <ul class="ml-4 mb-2 list-disc">
                                @foreach(['A','B','C','D'] as $opt)
                                    @php
                                        $optionText = $question->{'option_'.strtolower($opt)};
                                        if (!$optionText) continue; // skip empty options
                                        $isCorrect = strtoupper($correctAnswer) === $opt;
                                        $isSelected = strtoupper($studentAnswer) === $opt;
                                    @endphp
                                    <li>
                                        {{ $opt }}. {{ $optionText }}
                                        @if($revealAnswers)
                                            @if($isCorrect)
                                                — ✅ Correct Answer
                                            @endif
                                            @if($isSelected && !$isCorrect)
                                                — ❌ Student Selected
                                            @endif
                                            @if($isSelected && $isCorrect)
                                                — ✅ Student Selected
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @elseif($question->question_type === 'short')
                            @if($revealAnswers)
                                <p>
                                    <strong>Correct Answer:</strong> {{ $correctAnswer }} <br>
                                    <strong>Student Answer:</strong> {{ $studentAnswer }} <br>
                                    @if(strtoupper($studentAnswer) === strtoupper($correctAnswer))
                                        ✅ Correct
                                    @else
                                        ❌ Incorrect
                                    @endif
                                </p>
                            @else
                                <p><strong>Student Answer:</strong> {{ $studentAnswer }}</p>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>
@endsection
