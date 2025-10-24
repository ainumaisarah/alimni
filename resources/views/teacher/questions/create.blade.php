@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Add Question to: {{ $quiz->title }}</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.questions.store', $quiz->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Text</label>
            <textarea name="question_text" class="w-full border rounded p-2" rows="3">{{ old('question_text') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Option A</label>
            <input type="text" name="option_a" class="w-full border rounded p-2" value="{{ old('option_a') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Option B</label>
            <input type="text" name="option_b" class="w-full border rounded p-2" value="{{ old('option_b') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Option C</label>
            <input type="text" name="option_c" class="w-full border rounded p-2" value="{{ old('option_c') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Option D</label>
            <input type="text" name="option_d" class="w-full border rounded p-2" value="{{ old('option_d') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Correct Answer</label>
            <select name="correct_answer" class="w-full border rounded p-2">
                <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Add Question</button>
    </form>
</div>
@endsection
