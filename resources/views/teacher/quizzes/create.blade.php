@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.quizzes', request('classroom_id')) }}"
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
        <h2 class="mb-4">{{ isset($quiz) ? 'Edit Quiz' : 'Create Quiz' }}</h2>
    </div>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($quiz) ? route('teacher.quizzes.update', $quiz->id) : route('teacher.quizzes.store') }}" method="POST" class="info-card">
        @csrf
        @if(isset($quiz))
            @method('PUT')
        @endif

        {{-- Quiz Title --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quiz Title</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title', $quiz->title ?? '') }}" required>
        </div>

        {{-- Quiz Description --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Description (optional)</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3">{{ old('description', $quiz->description ?? '') }}</textarea>
        </div>

        {{-- Show Answers --}}
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="show_answers" value="1" {{ old('show_answers', $quiz->show_answers ?? false) ? 'checked' : '' }}>
                <span class="ml-2">Reveal answers to students after submission</span>
            </label>
        </div>

        {{-- Max Attempts --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">
                Maximum Attempts (1–3)
            </label>
            <select name="max_attempts" class="border rounded p-2 w-60" required>
                @for ($i = 1; $i <= 3; $i++)
                    <option value="{{ $i }}"
                        {{ old('max_attempts', $quiz->max_attempts ?? 1) == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Duration --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quiz Duration (minutes, optional)</label>
            <input type="number" name="duration" class="border rounded p-2 w-60" min="1" value="{{ old('duration', $quiz->duration ?? '') }}">
        </div>

        {{-- Open Date/Time --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Open Date & Time (optional)</label>
            <input type="datetime-local" name="open_at" class="border rounded p-2 w-60" value="{{ old('open_at', isset($quiz->open_at) ? $quiz->open_at->format('Y-m-d\TH:i') : '') }}">
        </div>

        {{-- Due Date/Time --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Due Date & Time (optional)</label>
            <input type="datetime-local" name="due_at" class="border rounded p-2 w-60" value="{{ old('due_at', isset($quiz->due_at) ? $quiz->due_at->format('Y-m-d\TH:i') : '') }}">
        </div>

        {{-- Classroom --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Classroom</label>
            <select name="classroom_id" class="w-full border rounded p-2" required>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $quiz->classroom_id ?? request('classroom_id')) == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">
            {{ isset($quiz) ? 'Update Quiz' : 'Create Quiz' }}
        </button>
    </form>
</div>
@endsection
