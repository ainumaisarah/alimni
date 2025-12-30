@extends('layouts.app')

@section('content')

<!-- ================= Welcome Banner ================= -->
<div class="schedule-container" style="margin-bottom:20px;">
    <div style="background-color:#7c3636; color:white; padding:20px; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:bold;">Welcome, {{ auth()->user()->name }}!</h1>
            <p style="color:white;">Here’s your latest class schedule and recently accessed classes.</p>
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

    @if ($schedules->isEmpty())
        <p>You are not assigned to a class or there are no schedules yet.</p>
    @else
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            // Generate 30-minute slots (08:00 – 14:00)
            $timeSlots = [];
            $current = \Carbon\Carbon::parse('08:00');
            $endTime = \Carbon\Carbon::parse('14:00');

            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i');
                $current->addMinutes(30);
            }

            // Prepare schedule map + skipped cells
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
                    'teacher'   => $schedule->teacher->name ?? 'N/A',
                ];

                for ($i = 1; $i < $rowspan; $i++) {
                    $skipSlots[$day][
                        $start->copy()->addMinutes(30 * $i)->format('H:i')
                    ] = true;
                }
            }
        @endphp

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
                        {{-- Time column --}}
                        <td class="border px-2 py-1 font-bold">{{ $time }}</td>

                        {{-- Day columns --}}
                        @foreach ($days as $day)

                            {{-- Skip slots covered by rowspan --}}
                            @if (!empty($skipSlots[$day][$time]))
                                @continue
                            @endif

                            {{-- Scheduled class --}}
                            @if (!empty($scheduleMap[$day][$time]))
                                <td
                                    class="border px-2 py-1 bg-blue-200 text-center"
                                    rowspan="{{ $scheduleMap[$day][$time]['rowspan'] }}"
                                    style="position: relative;"
                                >
                                    <div style="
                                        position: absolute;
                                        inset: 0;
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: center;
                                        align-items: center;
                                        font-weight: 600;
                                        text-align: center;
                                    ">
                                        <div>
                                            {{ $scheduleMap[$day][$time]['classroom'] }}
                                        </div>

                                        <div class="text-xs text-gray-700">
                                            {{ $scheduleMap[$day][$time]['teacher'] }}
                                        </div>
                                    </div>
                                </td>

                            {{-- Empty slot --}}
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

@if(!Auth::user()->consent_given_at)
<!-- Consent Modal -->
<div id="consentModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 transition-opacity duration-300">
    <div class="consent-container shadow-lg transform scale-95 transition-transform duration-300">
        <h2 class="text-xl font-bold mb-4">Consent Notice</h2>
        <p class="mt-4 mb-6 font-semibold">
            Your account was created by your school. Your personal data will be used for educational purposes only.
            Please read our <a href="{{ route('privacy.policy') }}" class="text-blue-600 underline" target="_blank">Privacy Policy</a>.
        </p>
        <form id="consentForm">
            @csrf
            <button type="submit" class="btn-primary center">
                I Acknowledge
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('consentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch("{{ route('consent.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        if(data.success){
            const modal = document.getElementById('consentModal');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.style.display = 'none', 300);
        }
    })
    .catch(err => console.error('Consent AJAX error:', err));
});

</script>
@endif
@endsection
