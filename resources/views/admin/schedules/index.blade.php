@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Manage Schedules</h2>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 text-blue px-4 py-2 rounded hover:bg-blue-700">
        + Create Schedule
    </a>

    <table class="mt-4 w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Classroom</th>
                <th class="border border-gray-300 px-4 py-2">Teacher</th>
                <th class="border border-gray-300 px-4 py-2">Day</th>
                <th class="border border-gray-300 px-4 py-2">Start Time</th>
                <th class="border border-gray-300 px-4 py-2">End Time</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->classroom->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->teacher->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->day }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->start_time }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->end_time }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-sm">Edit</a>

                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No schedules found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
