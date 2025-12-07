@extends('classes.show')

@section('content')
<div class="class-container">
<nav class="class-nav">
        <h2><a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
            {{ $class->name }}
        </a></h2>
        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}"
                class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}"> Materials </a>
             <a href="{{ route('classes.assignment', $class->id) }}"
                class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}"> Assignment </a>
            <a href="{{ route('classes.quizzes', $class->id) }}"
                class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}"> Quiz </a>
        </div>
</nav>
 {{-- Quizzes --}}
    <h3 style="font-size: 22px; font-weight: 650; color: #171818;">Quizzes</h3>

    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.quizzes.create', ['classroom_id' => $class->id]) }}"
           class="btn-primary mb-3 inline-block">Create New Quiz</a>
    @endif

    @if($quizzes->count() > 0)
        @foreach($quizzes as $quiz)

            @php
                $result = $quiz->results()->where('student_id', auth()->id())->latest()->first();
                $totalQuestions = $quiz->questions->count();
                $answeredQuestions = $result ? count($result->answers ?? []) : 0;
                $canRetake = (!$result) || ($answeredQuestions < $totalQuestions);
            @endphp

            <div class="app-card">
            @if(auth()->user()->role === 'student')
                @if($canRetake)
                    <h3>
                        <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="text-blue-600 hover:underline">
                            {{ $quiz->title }}
                        </a>
                    </h3>
                @else
                    <h3>
                        <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="text-yellow-600 hover:underline">
                            {{ $quiz->title }}
                        </a>
                    </h3>
                    <span class="info-meta ml-2">Score: {{ $result->score }}/{{ $totalQuestions }}
                    </span>
                @endif

                <p class="info-meta">Questions: {{ $totalQuestions }}</p>





                @elseif(auth()->user()->role === 'teacher')
                <h3 class="text-xl font-semibold">
                    <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="text-inherit no-underline hover:underline">
                        {{ $quiz->title }}
                    </a>
                </h3>



                    <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}" class="px-4 py-2 text-sm btn-secondary inline-block">Edit</p>
                    </a>
                    {{-- button view result
                    <a href="{{ route('teacher.quizzes.results', $quiz->id) }}">
                    <p class="btn-secondary">View Result</p>
                    </a>--}}

                    <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}"
                          method="POST"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm btn-danger inline-block"
                            onclick="return confirm('Are you sure you want to delete this quiz?');">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p class="empty-message">No quizzes available for this class yet.</p>
    @endif
</div>
@endsection
