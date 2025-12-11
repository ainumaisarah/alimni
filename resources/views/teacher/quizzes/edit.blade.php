@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <h2 class="mb-4">{{ isset($quiz) ? 'Edit Quiz' : 'Create Quiz' }}</h2>

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

        {{-- Duration --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Quiz Duration (minutes, optional)</label>
            <input type="number" name="duration" class="w-full border rounded p-2" min="1" value="{{ old('duration', $quiz->duration ?? '') }}">
        </div>

        {{-- Open Date/Time --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Open Date & Time (optional)</label>
            <input type="datetime-local" name="open_at" class="w-full border rounded p-2"
                value="{{ old('open_at', isset($quiz->open_at) ? \Carbon\Carbon::parse($quiz->open_at)->format('Y-m-d\TH:i') : '') }}">
        </div>

        {{-- Close Date/Time --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Close Date & Time (optional)</label>
            <input type="datetime-local" name="due_at" class="w-full border rounded p-2"
                value="{{ old('due_at', isset($quiz->due_at) ? \Carbon\Carbon::parse($quiz->due_at)->format('Y-m-d\TH:i') : '') }}">
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
