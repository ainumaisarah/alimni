@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('teacher.home') }}" :active="request()->routeIs('teacher.home')"
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
        <h2>Edit Announcement</h2>
    </div>

    <form action="{{ route('teacher.announcements.update', $announcement->id) }}"
          method="POST" class="info-card">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded"
                   value="{{ old('title', $announcement->title) }}" required>
        </div>

        <div class="mb-4">
            <label>Message</label>
            <textarea name="message" class="w-full border p-2 rounded" rows="4" required>{{ old('message', $announcement->message) }}</textarea>
        </div>

        <div class="mb-4">
            <label>Classroom</label>
            <select name="classroom_id" class="w-full border p-2 rounded">
                <option value="">All Classes</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $announcement->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">Update Announcement</button>
    </form>
</div>
@endsection
