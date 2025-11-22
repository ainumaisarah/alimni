@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">My Classes</h2>

    @if($classes->isEmpty())
        <p>No classes assigned yet.</p>
    @else
        @foreach($classes as $class)
            <div class="card mb-3 p-2 shadow-sm">
                <a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
                    {{ $class->name }}
                </a>

                @if($class->schedules->isNotEmpty())
                    <ul class="mt-2">
                        @foreach($class->schedules as $schedule)
                            <li>
                                Schedule: {{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
