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

            <div id="schedule-row" class="p-4 border rounded mb-4 relative">
                <div class="mb-2">
                    <label>Day</label>
                    <select name="day" class="w-full border rounded px-3 py-2" required>
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <option value="{{ $day }}" {{ $schedule->day == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Start Time</label>
                    <input type="time" name="start_time" value="{{ $schedule->start_time }}" class="border rounded px-3 py-2 w-full" required>
                </div>

                <div class="mb-2">
                    <label>End Time</label>
                    <input type="time" name="end_time" value="{{ $schedule->end_time }}" class="border rounded px-3 py-2 w-full" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Update Schedule</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classroomSelect = document.getElementById('classroom_id');
    const teacherSelect = document.getElementById('teacher_id');

    // Auto-select teacher when classroom changes
    function autoSelectTeacher() {
        const selectedOption = classroomSelect.options[classroomSelect.selectedIndex];
        if (!selectedOption) return;
        const teacherId = selectedOption.getAttribute('data-teacher');
        if (teacherId) teacherSelect.value = teacherId;
    }

    classroomSelect.addEventListener('change', autoSelectTeacher);
    autoSelectTeacher(); // run on page load
});
</script>
@endsection
