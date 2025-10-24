@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    <h2 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}</h2>

    {{-- Success message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- 📝 Post Announcement --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="text-xl font-semibold mb-3">Post New Announcement</h3>

        <form action="{{ route('teacher.announcements.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Message</label>
                <textarea name="message" class="w-full border p-2 rounded" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Classroom (optional)</label>
                <select name="classroom_id" class="w-full border p-2 rounded">
                    <option value="">All Classes</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
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

    <div class="bg-white p-6 rounded shadow">
    <h3 class="text-xl font-semibold mb-3">Recent Announcements</h3>

    @if($announcements->count() > 0)
        @foreach($announcements as $announcement)
            <div class="border p-3 rounded mb-2 flex justify-between items-start">
                <div>
                    <h4 class="font-semibold">{{ $announcement->title }}</h4>
                    <p>{{ $announcement->message }}</p>
                    <small class="text-gray-500">
                        Posted on {{ $announcement->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <div class="space-x-2">
                    {{-- Edit --}}
                    <a href="{{ route('teacher.announcements.edit', $announcement->id) }}"
                       class="text-blue-500 font-semibold">Edit</a>

                    {{-- Delete --}}
                    <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 font-semibold">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">No announcements yet.</p>
    @endif
</div>


</div>
@endsection
