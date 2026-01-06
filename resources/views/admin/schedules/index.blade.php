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

<table class="table-auto w-full border-collapse text-sm">
    <thead>
        <tr class="bg-gray-100 text-left">
            <th class="border px-4 py-2 w-1/6 text-center">Classroom</th>
            <th class="border px-4 py-2 w-1/6 text-center">Teacher</th>
            <th class="border px-4 py-2 w-1/6 text-center">Day</th>
            <th class="border px-4 py-2 w-1/6 text-center">Start Time</th>
            <th class="border px-4 py-2 w-1/6 text-center">End Time</th>
            <th class="border px-4 py-2 w-1/6 text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($schedules->groupBy(function($item) {
            return $item->classroom_id.'-'.$item->teacher_id;
        }) as $group)
            @foreach($group as $index => $schedule)
                <tr class="hover:bg-gray-50">
                    @if($index === 0)
                        <td class="border px-4 py-2 text-center align-middle" rowspan="{{ $group->count() }}">{{ $schedule->classroom->name }}</td>
                        <td class="border px-4 py-2 text-center align-middle" rowspan="{{ $group->count() }}">{{ $schedule->teacher->name }}</td>
                    @endif
                    <td class="border px-4 py-2 text-center">{{ $schedule->day }}</td>
                    <td class="border px-4 py-2 text-center">{{ $schedule->start_time }}</td>
                    <td class="border px-4 py-2 text-center">{{ $schedule->end_time }}</td>
                    <td class="border px-4 py-2 text-center align-middle">
                        <div class="flex flex-row justify-center items-center gap-2 whitespace-nowrap">
                            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn-secondary">Edit</a>
                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="6" class="empty-message text-center py-4">No schedules found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>
@endsection
