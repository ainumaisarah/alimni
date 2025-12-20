@extends('layouts.app')

@section('content')
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
        <h2>Edit Classroom</h2>
    </div>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST" class="space-y-4 max-w-lg">
        @csrf
        @method('PUT')

        <div class="app-card">
            <label for="name">Classroom Name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $classroom->name) }}"
                required
            >

            <label for="teacher_id">Assign Teacher</label>
            <select
                name="teacher_id"
                id="teacher_id"
            >
                <option value="">-- Select Teacher --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary">
                Update Classroom
            </button>
            <a href="{{ route('admin.classrooms.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
