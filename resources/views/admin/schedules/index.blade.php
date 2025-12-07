@extends('layouts.app')

@section('content')
<div class="schedule-container">
    <h2>Manage Schedules</h2>

    @if(session('success'))
        <div class = "success-alert mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.schedules.create') }}" class="btn-primary mb-3 mt-4">
        + Create Schedule
    </a>

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
