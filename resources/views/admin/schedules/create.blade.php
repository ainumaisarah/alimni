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
        <h2>Create Schedule</h2>
    </div>

    <div class = "app-card">
    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.schedules.store') }}" method="POST" class="max-w-lg">
        @csrf

        <div class="mb-4">
            <label for="classroom_id">Classroom</label>
            <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a classroom</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}"
                            data-teacher="{{ $classroom->teacher_id }}"
                            {{ (isset($selectedClassroomId) && $selectedClassroomId == $classroom->id) ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4"> <label for="teacher_id">Teacher</label> <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
        <option value="" disabled selected>Select a teacher</option>
        @foreach($teachers as $teacher) <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
        @endforeach
             </select>
        </div>

        <div class="mb-4">
            <label for="day">Day</label>
            <select name="day" id="day" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="btn-primary">Create Schedule</button>
    </form>
    </div>
</div>

{{-- Auto-select teacher JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const classroomSelect = document.getElementById('classroom_id');
    const teacherSelect = document.getElementById('teacher_id');

    function autoSelectTeacher() {
        const selectedOption = classroomSelect.options[classroomSelect.selectedIndex];
        if (!selectedOption) return;

        const teacherId = selectedOption.getAttribute('data-teacher');
        if (teacherId) {
            teacherSelect.value = teacherId;
        }
    }

    // ✅ run once on page load
    autoSelectTeacher();

    // ✅ run again when classroom changes
    classroomSelect.addEventListener('change', autoSelectTeacher);
});
</script>
@endsection
