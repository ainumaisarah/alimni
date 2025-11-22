@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Edit Quiz: {{ $quiz->title }}</h2>

    {{-- Quiz Info Form --}}
    <form action="{{ route('teacher.quizzes.update', $quiz->id) }}" method="POST" class="mb-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Quiz Title:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}"
                   class="border rounded px-3 py-2 w-full">
        </div>

        <div class="mb-4">
            <label for="classroom_id" class="block font-semibold mb-1">Classroom:</label>
            <select name="classroom_id" id="classroom_id" class="border rounded px-3 py-2 w-full">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $quiz->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-blue px-4 py-2 rounded">
            Update Quiz
        </button>
    </form>

    {{-- Questions List --}}
    <h3 class="text-xl font-semibold mb-2">Questions</h3>
    @if($quiz->questions->count() > 0)
        <ul class="mb-4">
            @foreach($quiz->questions as $question)
                <li class="mb-2 flex justify-between items-center border-b py-1">
                    <span>{{ $question->text }}</span>
                    <div class="space-x-2">
                        <a href="{{ route('teacher.questions.edit', $question->id) }}" class="text-blue-600">Edit</a>

                        <form action="{{ route('teacher.questions.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600" onclick="return confirm('Delete this question?')">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 mb-4">No questions yet.</p>
    @endif

    <a href="{{ route('teacher.questions.create', ['quiz' => $quiz->id]) }}"
       class="bg-green-500 text-blue px-4 py-2 rounded">
       Add New Question
    </a>
</div>
@endsection
