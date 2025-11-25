@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Your Class Schedule</h2>

    @if (count($schedules) === 0)
        <p>You are not assigned to a class or there are no schedules yet.</p>
    @else
        @php
            // Define days in order
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            // Define time slots
            $timeSlots = [
                '08:00', '09:00', '10:00', '11:00', '12:00',
                '13:00', '14:00', '15:00', '16:00',
            ];

            // Group schedules by day and start_time
            $scheduleMatrix = [];
            foreach ($schedules as $schedule) {
                $start = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                $scheduleMatrix[$schedule->day][$start][] = $schedule;
            }
        @endphp

        <table class="w-full border-collapse border border-gray-300 text-center">
            <thead>
                <tr>
                    <th class="border px-2 py-1">Time</th>
                    @foreach ($days as $day)
                        <th class="border px-2 py-1">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $time)
                    <tr>
                        <td class="border px-2 py-1 font-semibold">{{ $time }}</td>
                        @foreach ($days as $day)
                            <td class="border px-2 py-1 align-top">
                                @if (isset($scheduleMatrix[$day][$time]))
                                    @foreach ($scheduleMatrix[$day][$time] as $sched)
                                        <div class="mb-2 p-1 bg-blue-100 rounded">
                                            <div class="font-semibold text-gray-800">{{ $sched->classroom->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-700">{{ $sched->teacher->name ?? 'N/A' }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
