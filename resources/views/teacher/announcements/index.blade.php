@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Announcements</h2>

    @if($announcements->count() > 0)
        @foreach($announcements as $announcement)
            <div class="mb-4 p-4 border rounded shadow bg-yellow-50">
                <h3 class="font-semibold">{{ $announcement->title }}</h3>
                <p>{{ $announcement->message }}</p>
                <small class="text-gray-500">
                    Posted by {{ $announcement->teacher->name }} on {{ $announcement->created_at->format('d M Y, H:i') }}
                </small>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">No announcements yet.</p>
    @endif
</div>
@endsection
