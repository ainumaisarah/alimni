@extends('classes.show')

@section('content')
<div class="class-container">
    <nav class="class-nav">
            <div class="flex items-center gap-2">
            <a href="{{ route('classes.index') }}" :active="request()->routeIs('classes.index')"
                class="h-8 w-8 inline-flex items-center justify-center p-2" style="color: rgb(224, 216, 191);
                hover:text-[#1f4033]">
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
            <h2><a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
            {{ $class->name }}
        </a></h2>
        </div>

        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}" class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}">
                Materials
            </a>
            <a href="{{ route('classes.assignment', $class->id) }}" class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}">
                Assignment
            </a>
            <a href="{{ route('classes.quizzes', $class->id) }}" class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}">
                Quiz
            </a>
        </div>
    </nav>
    <div class="classbox">
    <h3 style="font-size: 22px; font-weight: 650; color: #2b5948;">
        Quizzes
    </h3>

    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.quizzes.create', ['classroom_id' => $class->id]) }}"
           class="btn-primary mb-3 inline-block">Create New Quiz</a>
    @endif

    @if($quizzes->count() > 0)

            @foreach($quizzes->sortByDesc('created_at') as $quiz)
                <div class="app-card p-3 border rounded shadow-sm">
                    @php
                        $now = now();
                        $isAvailable = true;
                        if ($quiz->open_at && $now->lt(\Carbon\Carbon::parse($quiz->open_at))) $isAvailable = false;
                        if ($quiz->due_at && $now->gt(\Carbon\Carbon::parse($quiz->due_at))) $isAvailable = false;

                        $result = $quiz->results()->where('student_id', auth()->id())->latest()->first();
                        $questionsCount = $quiz->questions->count();
                    @endphp

                    <h3 class="text-lg font-semibold">
                        @if(auth()->user()->role === 'teacher')
                            <a href="{{ route('teacher.questions.index', $quiz->id) }}" class="text-inherit hover:underline">
                                {{ $quiz->title }}
                            </a>
                        @else
                            <a href="{{ route('student.quizzes.single', $quiz->id) }}" class="text-blue-600 hover:underline">
                                {{ $quiz->title }}
                            </a>
                        @endif
                    </h3>


                    @if($quiz->description)
                        <p class="info-meta">Description: {{ $quiz->description }}</p>
                    @endif

                    @if($quiz->open_at)
                        <p class="info-meta">Open: {{ \Carbon\Carbon::parse($quiz->open_at)->format('d M Y H:i') }}</p>
                    @endif

                    @if($quiz->due_at)
                        <p class="info-meta">Close: {{ \Carbon\Carbon::parse($quiz->due_at)->format('d M Y H:i') }}</p>
                    @endif

                    @if($quiz->duration)
                        <p class="info-meta">Duration: {{ $quiz->duration }} minutes</p>
                    @endif

                    @if(auth()->user()->role === 'student')
                        <div class="mt-2">
                            @if(!$result)
                                <p class="text-red-600 font-semibold mb-1">Please attempt the quiz</p>
                            @endif

                            @if($isAvailable)
                                <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-secondary px-3 py-1 text-sm">
                                    Attempt
                                </a>
                            @else
                                <span class="text-gray-500 font-semibold">Quiz not available</span>
                            @endif
                        </div>
                    @endif

                    @if(auth()->user()->role === 'teacher')
                        <div class="mt-2 flex space-x-2">
                            <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}" class="btn-secondary px-3 py-1 text-sm">Edit</a>
                            <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger px-3 py-1 text-sm"
                                        onclick="return confirm('Are you sure you want to delete this quiz?');">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="empty-message">No quizzes available for this class yet.</p>
    @endif
</div>

<style>
.quiz-list {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 8px;
}
.quiz-list::-webkit-scrollbar {
    width: 6px;
}
.quiz-list::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 3px;
}
.info-meta {
    font-size: 0.875rem;
    color: #555;
}
</style>
@endsection
