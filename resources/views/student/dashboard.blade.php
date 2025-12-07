@extends('layouts.app')

@section('content')
<div class="schedule-container">
    <h2>Your Class Schedule</h2>

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

        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    @foreach ($days as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $time)
                    <tr>
                        <td class=>{{ $time }}</td>
                        @foreach ($days as $day)
                            <td>
                                @if (isset($scheduleMatrix[$day][$time]))
                                    @foreach ($scheduleMatrix[$day][$time] as $sched)
                                        <div class="schedule-item">
                                            <div class="classroom">{{ $sched->classroom->name ?? 'N/A' }}</div>
                                            <div class="teacher">{{ $sched->teacher->name ?? 'N/A' }}</div>
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
