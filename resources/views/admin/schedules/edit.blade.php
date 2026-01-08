@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.schedules.index') }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2 bg-gray-100 hover:bg-gray-200 rounded-lg">
            ←
        </a>
        <h2>Edit Schedule</h2>
    </div>

    <div class="app-card">
        @if ($errors->any())
            <div class="error-alert mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="max-w-3xl">
            @csrf
            @method('PUT')

            <!-- Classroom -->
            <div class="mb-4">
                <label>Classroom</label>
                <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled>Select a classroom</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}"
                                data-teacher="{{ $classroom->teacher_id }}"
                                {{ $schedule->classroom_id == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Teacher -->
            <div class="mb-4">
                <label>Teacher</label>
                <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled>Select a teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $schedule->teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="my-4">

            <div id="schedules-container">

                <!-- Existing schedule row -->
                @php
                    $start = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                    $end = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
                    $slot = (strtotime($end) - strtotime($start)) / 1800;
                    $times = ['08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00'];
                @endphp

                <div class="schedule-row border rounded p-4 mb-4 relative">

                    <button type="button" class="remove-schedule absolute top-2 right-2 text-red-500 font-bold">&times;</button>

                    <!-- Day -->
                    <div class="mb-3">
                        <label>Day</label>
                        <select name="schedules[0][day]" class="w-full border rounded px-3 py-2" required>
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <option value="{{ $day }}" {{ $schedule->day == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Time -->
                    <div class="mb-3">
                        <label>Start Time</label>
                        <select class="start-time-select w-full border rounded px-3 py-2" required>
                            @foreach($times as $time)
                                <option value="{{ $time }}" {{ $start == $time ? 'selected' : '' }}>{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Slot Count -->
                    <div class="mb-3">
                        <label>Duration</label>
                        <select class="slot-count w-full border rounded px-3 py-2" required>
                            <option value="1" {{ $slot == 1 ? 'selected' : '' }}>1 Slot (30 minutes)</option>
                            <option value="2" {{ $slot == 2 ? 'selected' : '' }}>2 Slots (60 minutes)</option>
                        </select>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="schedules[0][start_time]" class="start-time" value="{{ $start }}">
                    <input type="hidden" name="schedules[0][end_time]" class="end-time" value="{{ $end }}">
                    <p class="text-sm text-gray-600 mt-2 result-text">Time: {{ $start }} – {{ $end }}</p>

                </div>

            </div>

            <!-- <button type="button" id="add-schedule" class="btn-secondary mb-4">+ Add Another Day</button> -->
            <br>
            <button type="submit" class="btn-primary">Update Schedule</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let index = 1;
    const container = document.getElementById('schedules-container');
    const addBtn = document.getElementById('add-schedule');
    const classroomSelect = document.getElementById('classroom_id');
    const teacherSelect = document.getElementById('teacher_id');

    // Auto select teacher based on classroom
    classroomSelect.addEventListener('change', () => {
        const option = classroomSelect.options[classroomSelect.selectedIndex];
        teacherSelect.value = option.dataset.teacher;
    });

    function calculateTime(row) {
        const startSelect = row.querySelector('.start-time-select');
        const slotSelect = row.querySelector('.slot-count');
        if (!startSelect.value || !slotSelect.value) return;

        const start = startSelect.value;
        const slots = parseInt(slotSelect.value);

        const [h, m] = start.split(':').map(Number);
        const startMinutes = h * 60 + m;
        const endMinutes = startMinutes + (slots * 30);
        const endH = Math.floor(endMinutes / 60);
        const endM = endMinutes % 60;
        const endTime = `${String(endH).padStart(2,'0')}:${String(endM).padStart(2,'0')}`;

        row.querySelector('.start-time').value = start;
        row.querySelector('.end-time').value = endTime;
        row.querySelector('.result-text').innerText = `Time: ${start} – ${endTime}`;
    }

    function attachHandlers(row) {
        row.querySelector('.start-time-select')
            .addEventListener('change', () => calculateTime(row));
        row.querySelector('.slot-count')
            .addEventListener('change', () => calculateTime(row));
        row.querySelector('.remove-schedule')
            .addEventListener('click', () => {
                if(container.querySelectorAll('.schedule-row').length > 1){
                    row.remove();
                }
            });
    }

    attachHandlers(container.querySelector('.schedule-row'));

    addBtn.addEventListener('click', () => {
        const newRow = container.querySelector('.schedule-row').cloneNode(true);

        newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
        newRow.querySelectorAll('input').forEach(i => i.value = '');
        newRow.querySelector('.result-text').innerText = '';

        newRow.querySelectorAll('select, input').forEach(el => {
            if(el.name) el.name = el.name.replace(/\d+/, index);
        });

        container.appendChild(newRow);
        attachHandlers(newRow);
        index++;
    });

    // Fill hidden fields before submit
    document.querySelector('form').addEventListener('submit', () => {
        container.querySelectorAll('.schedule-row').forEach(row => calculateTime(row));
    });

});
</script>
@endsection
