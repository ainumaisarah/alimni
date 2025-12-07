@extends('layouts.app')

@section('content')
<div class="page-container">

    <h2>Welcome, {{ auth()->user()->name }}</h2>

    {{-- Success message --}}
    @if(session('success'))
        <div class="success-alert mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- 📝 Post Announcement --}}
    <div class="info-card">
        <h3>Post New Announcement</h3>

        <form action="{{ route('teacher.announcements.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="w-full border p-2 rounded" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label>Classroom</label>
                <select name="classroom_id" class="w-full border p-2 rounded">
                    <option value="">All Classes</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Post Announcement</button>
        </form>
    </div>

    {{-- Recent Announcements --}}
    <div class="info-card">
        <h3>Recent Announcements</h3>

        @if($announcements->count() > 0)
            @foreach($announcements as $announcement)
                <div class="info-card" style="padding:0.8rem; margin-bottom:0.5rem; display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <h3>{{ $announcement->title }}</h3>
                        <p>{{ $announcement->message }}</p>
                        <small class="info-meta">
                            Posted on {{ $announcement->created_at->format('d M Y, H:i') }}
                            @if($announcement->classroom)
                                &bull; Class: {{ $announcement->classroom->name }}
                            @else
                                &bull; All Classes
                            @endif
                        </small>
                    </div>
                    <div class="flex gap-2">
                        {{-- Edit Button --}}
                        <a style="font-size: 15.5px; font-weight: 670; color: #22493b;" href="{{ route('teacher.announcements.edit', $announcement->id) }}"
                           class="btn-secondary">Edit</a>

                        {{-- Delete Button --}}
                        <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}"
                              method="POST" onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger px-3 py-1 text-center">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p class="empty-message">No announcements yet.</p>
        @endif
    </div>

</div>
@endsection
