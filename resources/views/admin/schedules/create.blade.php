@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.schedules.index') }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                    bg-gray-100 hover:bg-gray-200 rounded-lg
                    text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2>Create Schedules</h2>
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

        <form action="{{ route('admin.schedules.storeMultiple') }}" method="POST" class="max-w-3xl">
            @csrf

            <div class="mb-4">
                <label>Classroom</label>
                <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled selected>Select a classroom</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}"
                                data-teacher="{{ $classroom->teacher_id }}"
                                {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Teacher</label>
                <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled selected>Select a teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="my-4">

            <div id="schedules-container">
                <!-- Schedule row template -->
                <div class="schedule-row mb-4 p-4 border rounded relative">
                    <button type="button" class="remove-schedule absolute top-2 right-2 text-red-500 font-bold">&times;</button>

                    <div class="mb-2">
                        <label>Day</label>
                        <select name="schedules[0][day]" class="w-full border rounded px-3 py-2" required>
                            <option value="" disabled selected>Select a day</option>
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Start Time</label>
                        <input type="time" name="schedules[0][start_time]" class="border rounded px-3 py-2 w-full" required>
                    </div>

                    <div class="mb-2">
                        <label>End Time</label>
                        <input type="time" name="schedules[0][end_time]" class="border rounded px-3 py-2 w-full" required>
                    </div>
                </div>
            </div>

            <button type="button" id="add-schedule" class="btn-secondary mb-4">+ Add Another Day/Time</button>
            <br>
            <button type="submit" class="btn-primary">Create Schedules</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let scheduleIndex = 1;

    const classroomSelect = document.getElementById('classroom_id');
    const teacherSelect = document.getElementById('teacher_id');
    const container = document.getElementById('schedules-container');
    const addBtn = document.getElementById('add-schedule');

    // Auto-select teacher based on classroom
    function autoSelectTeacher() {
        const selectedOption = classroomSelect.options[classroomSelect.selectedIndex];
        if (!selectedOption) return;
        const teacherId = selectedOption.getAttribute('data-teacher');
        if (teacherId) teacherSelect.value = teacherId;
    }

    classroomSelect.addEventListener('change', autoSelectTeacher);
    autoSelectTeacher();

    // Remove schedule row
    function attachRemoveHandler(row) {
        const removeBtn = row.querySelector('.remove-schedule');
        removeBtn.addEventListener('click', function () {
            if(container.querySelectorAll('.schedule-row').length > 1) {
                row.remove();
            }
        });
    }

    attachRemoveHandler(container.querySelector('.schedule-row'));

    // Add new schedule row
    addBtn.addEventListener('click', function () {
        const newRow = container.querySelector('.schedule-row').cloneNode(true);

        // Reset day and times
        newRow.querySelectorAll('select, input').forEach(el => {
            if(el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = '';
        });

        // Update name index
        newRow.querySelectorAll('select, input').forEach(el => {
            const name = el.getAttribute('name');
            el.setAttribute('name', name.replace(/\d+/, scheduleIndex));
        });

        container.appendChild(newRow);
        scheduleIndex++;

        attachRemoveHandler(newRow);
    });
});
</script>
@endsection
