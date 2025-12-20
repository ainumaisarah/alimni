@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.classrooms.index') }}"
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
        <h2>Create New Classroom</h2>
    </div>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.store') }}" method="POST">
        @csrf

        <div class="app-card">
            <label for="name">Classroom Name</label>
            <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}" required>



            <label for="teacher_id">Assign Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select Teacher --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>

        </div>

        <button type="submit" class="btn-primary">Create Classroom</button>
    </form>
</div>
@endsection
