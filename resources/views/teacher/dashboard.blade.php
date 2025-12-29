@extends('layouts.app')

@section('content')

<!-- ================= Welcome Banner ================= -->
<div class="schedule-container" style="margin-bottom:20px;">
    <div style="background-color:#7c3636; color:white; padding:20px; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:bold;">Welcome, {{ auth()->user()->name }}!</h1>
            <p style="color:white;">Here’s your teaching schedule and recently accessed classes.</p>
        </div>
        <div>
            <img src="{{ asset('images/gazelle.png') }}"
                 alt="Mountain Gazelle"
                 style="width:100px; height:100px; object-fit:contain; border-radius:8px;">
        </div>
    </div>
</div>

<!-- ================= Recently Accessed Classes ================= -->
@if ($recentClassrooms->isNotEmpty())
<div class="schedule-container mb-5">
    <h2 class="mb-3">Recently Accessed Classes</h2>
    <div class="flex gap-3 flex-wrap">
        @foreach ($recentClassrooms as $classroom)
            <a href="{{ route('classes.show', $classroom->id) }}"
               class="bg-gray-100 p-3 rounded shadow hover:shadow-md transition flex-1 min-w-[200px] text-black no-underline">
                <strong>{{ $classroom->name }}</strong>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- ================= Teaching Schedule ================= -->
<div class="schedule-container">
    <h2>Your Teaching Schedule</h2>

    @if ($schedules->isEmpty())
        <p>You have not been assigned to any schedules yet.</p>
    @else
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            // Time slots: 08:00 – 14:00 (30 min)
            $timeSlots = [];
            $current = \Carbon\Carbon::parse('08:00');
            $endTime = \Carbon\Carbon::parse('14:00');

            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i');
                $current->addMinutes(30);
            }

            // Schedule map with merged cells
            $scheduleMap = [];
            $skipSlots = [];

            foreach ($schedules as $schedule) {
                $start = \Carbon\Carbon::parse($schedule->start_time);
                $end   = \Carbon\Carbon::parse($schedule->end_time);

                $rowspan = $start->diffInMinutes($end) / 30;
                $day = $schedule->day;
                $startKey = $start->format('H:i');

                $scheduleMap[$day][$startKey] = [
                    'rowspan'   => $rowspan,
                    'classroom' => $schedule->classroom->name ?? 'N/A',
                ];

                for ($i = 1; $i < $rowspan; $i++) {
                    $skipSlots[$day][
                        $start->copy()->addMinutes(30 * $i)->format('H:i')
                    ] = true;
                }
            }
        @endphp

       <table class="border-collapse border w-full text-sm mt-3">
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
                        <td class="border px-2 py-1 font-bold">{{ $time }}</td>

                        @foreach ($days as $day)
                            @if(isset($skipSlots[$day][$time]))
                                {{-- skip merged slot --}}
                                @continue
                            @endif

                            @if(isset($scheduleMap[$day][$time]))
                                <td class="border px-2 py-1 bg-green-200 text-center"
                                    rowspan="{{ $scheduleMap[$day][$time]['rowspan'] }}"
                                    style="position: relative;">

                                    <div style="
                                        position: absolute;
                                        top: 0;
                                        bottom: 0;
                                        left: 0;
                                        right: 0;
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: center;
                                        align-items: center;
                                        font-weight: 600;
                                    ">
                                        {{ $scheduleMap[$day][$time]['classroom'] }}
                                    </div>
                                </td>
                            @else
                                <td class="border px-2 py-1">&nbsp;</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
