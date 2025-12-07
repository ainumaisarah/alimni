@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="mb-4">Create New Quiz</h2>

    <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="info-card">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Quiz Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Description (optional)</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="4"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Classroom</label>
            <select name="classroom_id" class="w-full border p-2 rounded" required>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}"
                        @if(isset($classroomId) && $classroomId == $classroom->id) selected @endif>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">
            Create Quiz
        </button>
    </form>

</div>
@endsection
