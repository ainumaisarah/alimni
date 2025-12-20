@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.users.index') }}"
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
        <h2>Enroll Student: {{ $student->name }}</h2>
    </div>

    <form action="{{ route('admin.users.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="classroom_id" class="block font-medium mb-2">Select Classroom</label>
        <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2 mb-4">
            <option value="">-- No Classroom Assigned --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ $student->classroom_id == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>

        @error('classroom_id')
            <p class="error-alert mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary">
            Save Enrollment
        </button>
    </form>
</div>
@endsection
