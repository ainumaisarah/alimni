@extends('layouts.app')

@section('content')

<!-- ================= Welcome Banner ================= -->
<div class="schedule-container" style="margin-bottom:20px;">
    <div style="background-color:#7c3636; color:white; padding:20px; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:bold;">Welcome, {{ auth()->user()->name }}!</h1>
            <p>Here’s your latest class schedule and recently accessed classes.</p>
        </div>
        <div>
            <img src="{{ asset('images/gazelle.png') }}"
                 alt="Mountain Gazelle"
                 style="width:100px; height:100px; object-fit:contain; border-radius:8px;">
        </div>
    </div>
</div>

<!-- ================= Recently Accessed Classes ================= -->
@if($recentClassrooms->isNotEmpty())
<div class="schedule-container" style="margin-bottom:20px;">
    <h2>Recently Accessed Classes</h2>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        @foreach($recentClassrooms as $classroom)
            <a href="{{ route('classes.show', $classroom->id) }}" style="background:#f3f3f3; padding:10px; border-radius:8px; text-decoration:none; color:black; flex:1 1 200px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-weight:bold;">{{ $classroom->name }}</div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- ================= Schedule Table ================= -->
<div class="schedule-container">
    <h2>Your Class Schedule</h2>

    @if (count($schedules) === 0)
        <p>You are not assigned to a class or there are no schedules yet.</p>
    @else
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $timeSlots = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
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
                        <td>{{ $time }}</td>
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
