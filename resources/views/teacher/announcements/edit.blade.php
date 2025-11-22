@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Edit Announcement</h2>

    <form action="{{ route('teacher.announcements.update', $announcement->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border p-2 rounded" value="{{ old('title', $announcement->title) }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Message</label>
            <textarea name="message" class="w-full border p-2 rounded" rows="4" required>{{ old('message', $announcement->message) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Classroom</label>
            <select name="classroom_id" class="w-full border p-2 rounded">
                <option value="">All Classes</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $announcement->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="bg-blue-500 text-blue px-4 py-2 rounded">Update Announcement</button>
    </form>
</div>
@endsection
