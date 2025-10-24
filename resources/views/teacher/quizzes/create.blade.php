@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Create New Quiz</h2>

    <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Quiz Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Description (optional)</label>
            <textarea name="description" class="w-full border p-2 rounded"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Subject</label>
            <select name="subject_id" class="w-full border p-2 rounded" required>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Classroom</label>
            <select name="classroom_id" class="w-full border p-2 rounded" required>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Create Quiz</button>
    </form>
</div>
@endsection
