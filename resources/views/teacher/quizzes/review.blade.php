@extends('layouts.app')

@section('content')
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

        <h2 class="text-2xl font-semibold">
            Review: {{ $quiz->title }}
            (Student: {{ $result->student->name }},
            Attempt #{{ $result->attempt_number }})
        </h2>
    </div>

    @php
        $hasPendingShort = $questions
            ->where('question_type', 'short')
            ->filter(fn($q) => !isset($result->answers[$q->id.'_marks']))
            ->count() > 0;
    @endphp

    <form action="{{ route('teacher.quizzes.submit_grades', [$quiz->id, $result->id]) }}"
          method="POST">
        @csrf

        <p class="mb-4 font-semibold">
            Current Score: {{ $result->score }}%
            @if($hasPendingShort)
                <span class="text-orange-600 font-medium">
                    (Short answers pending grading)
                </span>
            @endif
        </p>

        <div class="space-y-4">
            @foreach($questions as $question)
                @php
                    $studentAnswer = $result->answers[$question->id] ?? 'Not answered';
                    $earnedMarks = $result->answers[$question->id.'_marks'] ?? null;
                    $isCorrect = (
                        $question->question_type === 'mcq' &&
                        strtoupper($studentAnswer) === strtoupper($question->correct_answer)
                    );
                @endphp

                <div class="info-card p-4 border rounded">
                    <p class="font-semibold mb-2">
                        {{ $loop->iteration }}. {{ $question->question_text }}
                    </p>

                    <p class="mb-2">
                        <strong>Student Answer:</strong>
                        {{ $studentAnswer }}
                    </p>

                    {{-- MCQ --}}
                    @if($question->question_type === 'mcq')
                        <p>
                            <strong>Marks:</strong>
                            {{ $earnedMarks ?? ($isCorrect ? $question->marks_mcq : 0) }}
                            / {{ $question->marks_mcq }}
                        </p>

                        @if($quiz->show_answers)
                            <p class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                                <strong>Correct Answer:</strong>
                                {{ $question->correct_answer }}
                            </p>
                        @endif

                    {{-- SHORT ANSWER --}}
                    @else
                        <div class="mt-2">
                            <label class="block font-semibold mb-1">
                                Marks (0 – {{ $question->marks_short }})
                            </label>

                            <input type="number"
                                   name="grades[{{ $question->id }}]"
                                   min="0"
                                   max="{{ $question->marks_short }}"
                                   value="{{ old('grades.'.$question->id, $earnedMarks) }}"
                                   class="border rounded p-2 w-32">

                            <p class="text-sm text-gray-600 mt-1">
                                Total marks: {{ $question->marks_short }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($hasPendingShort)
            <button type="submit" class="btn-primary mt-6">
                Save Grades & Recalculate Score
            </button>
        @endif
    </form>
</div>
@endsection
