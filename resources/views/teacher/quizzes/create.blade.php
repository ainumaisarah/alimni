@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Add Question to {{ $quiz->title }}</h2>

    <form action="{{ route('teacher.questions.store', $quiz->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Text</label>
            <textarea name="question_text" class="w-full border p-2 rounded" required></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold mb-1">Option A</label>
                <input type="text" name="option_a" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Option B</label>
                <input type="text" name="option_b" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Option C</label>
                <input type="text" name="option_c" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Option D</label>
                <input type="text" name="option_d" class="w-full border p-2 rounded" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Correct Answer</label>
            <select name="correct_answer" class="w-full border p-2 rounded" required>
                <option value="">Select</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Question</button>
    </form>
</div>
@endsection
