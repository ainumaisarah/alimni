@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('teacher.questions.index', $quiz->id) }}"
        class="h-8 w-8 inline-flex items-center justify-center p-2
                bg-gray-100 hover:bg-gray-200 rounded-lg
                text-[#2b5948] hover:text-[#1f4033]">
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
        <h2 class="mb-4">{{ isset($question) ? 'Edit' : 'Add' }} Question for: {{ $quiz->title }}</h2>
    </div>

    <div class = "info-card">
    <form action="{{ isset($question) ? route('teacher.questions.update', [$quiz->id, $question->id]) : route('teacher.questions.store', $quiz->id) }}"
          method="POST" x-data="{ type: '{{ old('question_type', $question->question_type ?? '') }}' }">
        @csrf
        @if(isset($question))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Text</label>
            <textarea name="question_text" class="w-full border rounded p-2" rows="3">{{ old('question_text', $question->question_text ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Question Type</label>
            <select name="question_type" x-model="type" class="w-full border rounded p-2">
                <option value="">Select type</option>
                <option value="mcq">MCQ</option>
                <option value="short">Short Answer</option>
            </select>
        </div>

        {{-- MCQ Options --}}
        <template x-if="type === 'mcq'">
            <div>
                <div class="mb-2">
                    <label class="block font-semibold">Option A</label>
                    <input type="text" name="option_a" class="w-full border rounded p-2"
                           value="{{ old('option_a', $question->option_a ?? '') }}">
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Option B</label>
                    <input type="text" name="option_b" class="w-full border rounded p-2"
                           value="{{ old('option_b', $question->option_b ?? '') }}">
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Option C (Optional)</label>
                    <input type="text" name="option_c" class="w-full border rounded p-2"
                           value="{{ old('option_c', $question->option_c ?? '') }}">
                </div>
                <div class="mb-2">
                    <label class="block font-semibold">Option D (Optional)</label>
                    <input type="text" name="option_d" class="w-full border rounded p-2"
                           value="{{ old('option_d', $question->option_d ?? '') }}">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Correct Answer</label>
                    <select name="correct_answer" class="w-full border rounded p-2">
                        <option value="">Select correct option</option>
                        <option value="A" @if(old('correct_answer', $question->correct_answer ?? '') === 'A') selected @endif>A</option>
                        <option value="B" @if(old('correct_answer', $question->correct_answer ?? '') === 'B') selected @endif>B</option>
                        <option value="C" @if(old('correct_answer', $question->correct_answer ?? '') === 'C') selected @endif>C</option>
                        <option value="D" @if(old('correct_answer', $question->correct_answer ?? '') === 'D') selected @endif>D</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">MCQs always have 1 mark by default.</p>
                </div>
            </div>
        </template>

        {{-- Short Answer Marks --}}
        <div x-show="type === 'short'" class="mb-4">
            <label class="block font-semibold mb-1">Marks for this question</label>
            <input type="number"
                name="marks_short"
                min="1"
                class="w-full border rounded p-2"
                x-bind:value="{{ old('marks_short', 2) }}">
            <p class="text-sm text-gray-500 mt-1">
                MCQ questions are automatically 1 mark.
            </p>
        </div>

        <button type="submit" class="btn-primary px-4 py-2 rounded">
            {{ isset($question) ? 'Update' : 'Add' }} Question
        </button>
    </form>
    </div>
</div>
@endsection
