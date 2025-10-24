@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Post New Announcement</h2>

    <form action="{{ route('teacher.announcements.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Message</label>
            <textarea name="message" class="w-full border p-2 rounded" rows="4" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Classroom (optional)</label>
            <select name="classroom_id" class="w-full border p-2 rounded">
                <option value="">All Classes</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Subject (optional)</label>
            <select name="subject_id" class="w-full border p-2 rounded">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Post Announcement</button>
    </form>
</div>
@endsection
