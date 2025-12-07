@extends('layouts.app')

@section('content')
<div class="page-container mt-4">
    <h2>My Classes</h2>

    @if($classes->isEmpty())
        <p class="empty-message">No classes assigned yet.</p>
    @else
    <div class="class-list-grid">
        @foreach($classes as $class)
            <div class="app-card">
                <a href="{{route('classes.show', $class->id) }}">
                    <img src="{{ asset('images/file.png') }}" alt="file" class="file">
                </a>
                <a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
                    {{ $class->name }}
                </a>

                @if($class->schedules->isNotEmpty())
                    <ul class="mt-2">
                        @foreach($class->schedules as $schedule)
                            <li class="info-meta">
                                {{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
