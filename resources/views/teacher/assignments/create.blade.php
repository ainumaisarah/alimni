@extends('layouts.app')

@section('content')
<div class = "page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.assignment', request('classroom_id')) }}"
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
        <h2>Create New Assignment</h2>
    </div>


<div class="info-card">
<form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="hidden" name="classroom_id" value="{{ $classroom_id }}">

    <div>
        <label class="font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2"></textarea>
    </div>

    <div>
    <label class="font-medium">Due</label>
    <input type="datetime-local"
           name="due_at"
           value="{{ old('due_at') }}"
           class="w-full border rounded p-2">
    </div>



    <div>
        <label class="font-medium">Upload File</label>
        <input type="file" name="file" class="w-full border rounded p-2">
    </div>

    <button type="submit" class="btn-primary">Create Assignment</button>
</form>
</div>
</div>
@endsection
