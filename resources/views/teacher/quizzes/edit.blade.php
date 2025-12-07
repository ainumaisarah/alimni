@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="mb-4">Edit Quiz: {{ $quiz->title }}</h2>

    {{-- Quiz Info Form --}}
    <form action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="POST" class="info-card mb-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Quiz Title:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}"
                   class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="classroom_id" class="block font-semibold mb-1">Classroom:</label>
            <select name="classroom_id" id="classroom_id" class="w-full border p-2 rounded">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $quiz->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">
            Update Quiz
        </button>
    </form>

    {{-- Questions List --}}
    <h3>Questions</h3>

    @if($quiz->questions->count() > 0)
        <ul class="mb-4">
            @foreach($quiz->questions as $question)
                <li class="app-card mb-2 flex justify-between items-center">
                    <span>{{ $question->question_text }}</span>
                    <div class="flex gap-2">
                        <a href="{{ route('teacher.questions.edit', $question->id) }}"
                           class="btn-secondary">
                            Edit
                        </a>
                        <form action="{{ route('teacher.questions.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn-danger"
                                    onclick="return confirm('Delete this question?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="empty-message mb-4">No questions yet.</p>
    @endif

    <a href="{{ route('teacher.questions.create', ['quiz' => $quiz->id]) }}"
       class="btn-primary">
       Add New Question
    </a>
</div>
@endsection
