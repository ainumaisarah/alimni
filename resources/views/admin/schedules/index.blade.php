@extends('layouts.app')

@section('content')
<div class="page-container">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Manage Schedules</h2>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="success-alert mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🔴 Unscheduled classrooms --}}
    @if($unscheduledClassrooms->count())
            <div class="app-card border-l-4 border-red-500 mb-8 w-full max-w-none">
            <h3 class="text-lg font-semibold text-red-600 mb-2">
                ⚠ {{ $unscheduledClassrooms->count() }} classes have not been scheduled yet
            </h3>

                <ul class="divide-y text-gray-700">
                @foreach($unscheduledClassrooms as $classroom)
                    <li class="flex items-center justify-between py-1">
                        <span>{{ $classroom->name }}</span>

                        <a href="{{ route('admin.schedules.create', ['classroom_id' => $classroom->id]) }}"
                        class="text-sm text-blue-600 hover:underline">
                            Assign schedule →
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="mb-2 font-semibold">Scheduled Classes</h3>

    <table>
        <thead>
            <tr>
                <th>Classroom</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->classroom->name }}</td>
                    <td>{{ $schedule->teacher->name }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ $schedule->start_time }}</td>
                    <td>{{ $schedule->end_time }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn-secondary">Edit</a>

                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-message">No schedules found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
