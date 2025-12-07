@extends('layouts.app')

@section('content')
<div class="schedule-container">
    <h2> Teacher Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}!</p>
<br>
    <h3>Your Teaching Schedule</h3>

    @if ($schedules->isEmpty())
        <p>You have not been assigned to any schedules yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="border px-4 py-2">Classroom</th>
                    <th class="border px-4 py-2">Day</th>
                    <th class="border px-4 py-2">Start Time</th>
                    <th class="border px-4 py-2">End Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
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
