@extends('classes.show')

@section('content')
<div class="class-container">
    <nav class="class-nav">
        <div class="flex items-center gap-2">
            <a href="{{ route('classes.index') }}"
               class="h-8 w-8 inline-flex items-center justify-center p-2"
               style="color: rgb(224, 216, 191);">
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
            <h2>
                <a href="{{ route('classes.show', $class->id) }}"
                   class="font-semibold text-lg">
                    {{ $class->name }}
                </a>
            </h2>
        </div>

        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}">Materials</a>
            <a href="{{ route('classes.assignment', $class->id) }}">Assignment</a>
            <a href="{{ route('classes.quizzes', $class->id) }}" class="active">Quiz</a>
        </div>
    </nav>

    <div class="classbox">
        <h3 style="font-size: 22px; font-weight: 650; color: #2b5948;">
            Quizzes
        </h3>

        @if(auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.quizzes.create', ['classroom_id' => $class->id]) }}"
               class="btn-primary mb-3 inline-block">
                Create New Quiz
            </a>
        @endif

        @if($quizzes->count() > 0)
            @foreach($quizzes->sortByDesc('created_at') as $quiz)
                @php
                    $now = now();
                    $isAvailable = true;

                    if ($quiz->open_at && $now->lt($quiz->open_at)) $isAvailable = false;
                    if ($quiz->due_at && $now->gt($quiz->due_at)) $isAvailable = false;

                    $questionsCount = $quiz->questions->count();
                    $maxAttempts = $quiz->max_attempts;

                    $attemptsCount = $quiz->results()
                        ->where('student_id', auth()->id())
                        ->count();
                @endphp

                <div class="app-card p-3 border rounded shadow-sm mb-3">

                    {{-- Quiz Title --}}
                    <h3 class="text-lg font-semibold
                        @if(auth()->user()->role === 'teacher' && $questionsCount == 0)
                            error-alert
                        @endif
                    ">
                        @if(auth()->user()->role === 'teacher')
                            <a href="{{ route('teacher.questions.index', $quiz->id) }}"
                               class="hover:underline">
                                {{ $quiz->title }}
                            </a>
                        @else
                            <a href="{{ route('student.quizzes.single', $quiz->id) }}"
                               class="text-blue-600 hover:underline">
                                {{ $quiz->title }}
                            </a>
                        @endif
                    </h3>

                    {{-- Metadata --}}
                    @if($quiz->description)
                        <p class="info-meta">Description: {{ $quiz->description }}</p>
                    @endif

                    @if($quiz->open_at)
                        <p class="info-meta">Open: {{ $quiz->open_at->format('d M Y H:i') }}</p>
                    @endif

                    @if($quiz->due_at)
                        <p class="info-meta">Close: {{ $quiz->due_at->format('d M Y H:i') }}</p>
                    @endif

                    @if($quiz->duration)
                        <p class="info-meta">Duration: {{ $quiz->duration }} minutes</p>
                    @endif

                    <p class="info-meta">
                        Questions: <strong>{{ $questionsCount }}</strong>
                    </p>

                    {{-- Max Attempts (student + teacher) --}}
                    <p class="info-meta">
                        Attempts Allowed:
                        <strong>{{ $maxAttempts }}</strong>
                    </p>

                    {{-- Student Actions --}}
                    @if(auth()->user()->role === 'student')
                        <div class="mt-2">
                            @if(!$isAvailable || $questionsCount == 0)
                                <span class="text-gray-500 font-semibold">
                                    The quiz is not available
                                </span>
                            @elseif($attemptsCount >= $maxAttempts)
                                {{-- NO BUTTON when attempts finished --}}
                                <span class="text-gray-500 font-semibold">
                                    You have finished all attempts
                                </span>
                            @else
                                <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                                   class="btn-secondary px-3 py-1 text-sm">
                                    Attempt Quiz
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Teacher Actions --}}
                    @if(auth()->user()->role === 'teacher')
                        <div class="mt-2 flex space-x-2">

                            @if($questionsCount == 0)
                                <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('teacher.questions.create', $quiz->id) }}"
                                   class="btn-primary px-3 py-1 text-sm">
                                    + Add Question
                                </a>
                            @else
                                <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('teacher.questions.index', $quiz->id) }}"
                                   class="btn-primary px-3 py-1 text-sm">
                                    Manage Quiz
                                </a>
                            @endif

                            <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                               class="btn-secondary px-3 py-1 text-sm">
                                Edit Quiz
                            </a>

                            <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-danger px-3 py-1 text-sm"
                                        onclick="return confirm('Delete this quiz?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endforeach
        @else
            <p class="empty-message">No quizzes available for this class yet.</p>
        @endif
    </div>
</div>

<style>
.info-meta {
    font-size: 0.875rem;
    color: #555;
}
</style>
@endsection
