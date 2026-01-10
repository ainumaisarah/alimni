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

    <form method="GET" class="mb-6 max-w-sm">
    <label class="block text-sm font-medium mb-1">
        View schedule by teacher
    </label>

    <select name="teacher_id"
            onchange="this.form.submit()"
            class="w-full border rounded px-3 py-2">
        <option value="">Select a teacher</option>

        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}"
                {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                {{ $teacher->name }}
            </option>
        @endforeach
    </select>
</form>

@if ($selectedTeacher)

    <div class="schedule-container mb-10">
        <h2 class="text-lg font-bold mb-3">
            Teaching Schedule – {{ $selectedTeacher->name }}
        </h2>

        @php
            $schedules = $selectedTeacher->schedules;

            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

            $timeSlots = [];
            $current = \Carbon\Carbon::parse('08:00');
            $endTime = \Carbon\Carbon::parse('14:00');

            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i');
                $current->addMinutes(30);
            }

            $scheduleMap = [];
            $skipSlots = [];

            foreach ($schedules as $schedule) {
                $start = \Carbon\Carbon::parse($schedule->start_time);
                $end   = \Carbon\Carbon::parse($schedule->end_time);

                $rowspan = $start->diffInMinutes($end) / 30;
                $day = $schedule->day;
                $startKey = $start->format('H:i');

                $classroom = $schedule->classroom->name ?? 'N/A';

                $hash = crc32($classroom);
                $color = "hsl(" . ($hash % 360) . ", 70%, 85%)";

                $scheduleMap[$day][$startKey] = [
                    'rowspan' => $rowspan,
                    'classroom' => $classroom,
                    'color' => $color,
                    'schedule_id' => $schedule->id,
                ];

                for ($i = 1; $i < $rowspan; $i++) {
                    $skipSlots[$day][
                        $start->copy()->addMinutes(30 * $i)->format('H:i')
                    ] = true;
                }
            }
        @endphp

        @if ($schedules->isEmpty())
            <p class="text-gray-500 text-sm">No schedules assigned.</p>
        @else
            <table class="border-collapse border w-full text-sm">
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
                                @if(isset($skipSlots[$day][$time]))
                                    @continue
                                @endif

                                @if(isset($scheduleMap[$day][$time]))
                                    <td rowspan="{{ $scheduleMap[$day][$time]['rowspan'] }}"
                                        class="border text-center font-medium relative"
                                        style="background: {{ $scheduleMap[$day][$time]['color'] }}">

                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <span>{{ $scheduleMap[$day][$time]['classroom'] }}</span>

                                            <a href="{{ route('admin.schedules.edit', $scheduleMap[$day][$time]['schedule_id']) }}"
                                            class="text-xs text-blue-700 underline hover:text-blue-900">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                @else
                                    <td class="border">&nbsp;</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endif


</div>
@endsection
