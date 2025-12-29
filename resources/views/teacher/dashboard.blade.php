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

<div class="schedule-container">
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
