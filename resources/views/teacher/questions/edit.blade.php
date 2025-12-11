@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="mb-4">Edit Question</h2>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.questions.update', [$quiz->id, $question->id]) }}" class = "info-card" method="POST"
        x-data="{ type: '{{ old('question_type', 'mcq') }}' }">
        @csrf
        @method('PUT')

        {{-- Question Text --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Text</label>
            <textarea name="question_text" class="w-full border rounded p-2" rows="3">{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        {{-- Question Type --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Type</label>
            <select name="question_type" class="w-full border rounded p-2" x-model="type">
                <option value="mcq">Multiple Choice (MCQ)</option>
                <option value="short">Short Answer</option>
            </select>
        </div>

        {{-- MCQ FIELDS --}}
        <div x-show="type === 'mcq'">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Option A</label>
                <input type="text" name="option_a" class="w-full border rounded p-2" value="{{ old('option_a', $question->option_a) }}">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Option B</label>
                <input type="text" name="option_b" class="w-full border rounded p-2" value="{{ old('option_b', $question->option_b) }}">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Option C (optional)</label>
                <input type="text" name="option_c" class="w-full border rounded p-2" value="{{ old('option_c', $question->option_c) }}">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Option D (optional)</label>
                <input type="text" name="option_d" class="w-full border rounded p-2" value="{{ old('option_d', $question->option_d) }}">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Correct Answer</label>
                <select name="correct_answer" class="w-full border rounded p-2">
                    <option value="A" {{ $question->correct_answer === 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $question->correct_answer === 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $question->correct_answer === 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ $question->correct_answer === 'D' ? 'selected' : '' }}>D</option>
                </select>
            </div>
        </div>

        {{-- SHORT ANSWER FIELD --}}
        <div x-show="type === 'short'">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Short Answer (Correct Answer)</label>
                <input type="text" name="short_answer" class="w-full border rounded p-2"
                    value="{{ old('short_answer', $question->short_answer) }}">
            </div>
        </div>

        <button type="submit" class="btn-primary">Update Question</button>
    </form>

</div>

<script src="https://unpkg.com/alpinejs" defer></script>
@endsection
