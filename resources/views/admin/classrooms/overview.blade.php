@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Classroom Overview</h2>

    @foreach ($classrooms as $classroom)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $classroom->name }}</strong>
            </div>
            <div class="card-body">
                <h5>Teacher(s):</h5>
                <ul>
                    @foreach ($classroom->schedules as $schedule)
                        @if($schedule->teacher)
                            <li>{{ $schedule->teacher->name }} ({{ $schedule->subject }})</li>
                        @endif
                    @endforeach
                </ul>

                <h5>Students:</h5>
                @if($classroom->students->count())
                    <ul>
                        @foreach ($classroom->students as $student)
                            <li>{{ $student->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No students enrolled.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
