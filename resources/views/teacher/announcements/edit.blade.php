@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Edit Announcement</h2>

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
