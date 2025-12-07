@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Announcements</h2>

    @if($announcements->count() > 0)
        @foreach($announcements as $announcement)
            <div class="info-card">
                <h3>{{ $announcement->title }}</h3>
                <p>{{ $announcement->message }}</p>
                <small class="info-meta">
                    Posted by {{ $announcement->teacher->name }} on {{ $announcement->created_at->format('d M Y, H:i') }}
                </small>
            </div>
        @endforeach
    @else
        <p class="empty-message">No announcements yet.</p>
    @endif
</div>
@endsection
