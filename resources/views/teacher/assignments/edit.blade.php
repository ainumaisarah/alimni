@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.assignment', $assignment->classroom_id) }}"
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
        <h2>Edit Assignment</h2>
    </div>


<form action="{{ route('teacher.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="info-card mb-6">
    @csrf
    @method('PUT')

    <div>
        <label class="font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded p-2" value="{{ $assignment->title }}" required>
    </div>

    <div>
        <label class="font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2">{{ $assignment->description }}</textarea>
    </div>

    <div>
    <label class="font-medium">Due</label>
    <input type="datetime-local"
       name="due_at"
       value="{{ old('due_at', optional($assignment)->due_at ? \Carbon\Carbon::parse($assignment->due_at)->format('Y-m-d\TH:i') : '') }}"
       class="w-full border rounded p-2">
    </div>


    <div>
        <label class="font-medium">Upload New File</label>
        <input type="file" name="file" class="w-full border rounded p-2">
        @if($assignment->file)
            <p class="mt-2">Current file: <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="text-blue-600 underline">{{ basename($assignment->file) }}</a></p>
        @endif
    </div>

    <div>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="allow_late_submission" value="1"
                {{ $assignment->allow_late_submission ? 'checked' : '' }}>
            <span class="font-medium">Allow late submission</span>
        </label>
    </div>


    <button type="submit" class="btn-primary">Update Assignment</button>
</form>

<form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" class="mt-4">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger" onclick="return confirm('Are you sure?')">Delete Assignment</button>
</form>
</div>
@endsection
