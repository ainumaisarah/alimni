@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Classroom Overview</h2>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($classrooms as $classroom)
            <div class="app-card">
                <h3>
                    {{ $classroom->name }}
                </h3>

                <div class="p-4 space-y-3">
                    <!-- Teacher Section -->
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-1">Teacher:</h5>
                        <ul class="list-disc list-inside text-gray-600">
                            @if($classroom->teacher)
                                <li>{{ $classroom->teacher->name }}</li>
                            @else
                                <li class="text-gray-400">No teacher assigned</li>
                            @endif

                            @foreach ($classroom->schedules as $schedule)
                                @if($schedule->teacher && $schedule->subject)
                                    <li>{{ $schedule->teacher->name }} ({{ $schedule->subject }})</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <!-- Students Section -->
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-1">Students:</h5>
                        @if($classroom->students->count())
                            <ul class="list-disc list-inside text-gray-600">
                                @foreach ($classroom->students as $student)
                                    <li>{{ $student->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No students enrolled</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
