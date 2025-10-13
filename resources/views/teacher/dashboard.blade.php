@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold text-gray-800 leading-tight mb-4">
        Teacher Dashboard
    </h2>
    <p class="mb-6">Welcome, {{ auth()->user()->name }}!</p>

    <h3 class="text-lg font-medium mb-2">Your Teaching Schedule</h3>

    @if ($schedules->isEmpty())
        <p>You have not been assigned to any schedules yet.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Subject</th>
                    <th class="border px-4 py-2">Classroom</th>
                    <th class="border px-4 py-2">Day</th>
                    <th class="border px-4 py-2">Start Time</th>
                    <th class="border px-4 py-2">End Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td class="border px-4 py-2">{{ $schedule->subject }}</td>
                        <td class="border px-4 py-2">{{ $schedule->classroom->name }}</td>
                        <td class="border px-4 py-2">{{ $schedule->day }}</td>
                        <td class="border px-4 py-2">{{ $schedule->start_time }}</td>
                        <td class="border px-4 py-2">{{ $schedule->end_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
