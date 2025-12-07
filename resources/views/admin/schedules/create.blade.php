@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Create Schedule</h2>

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
                    <option value="{{ $classroom->id }}" data-teacher="{{ $classroom->teacher_id }}">
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="teacher_id">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
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
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="btn-primary">Create Schedule</button>
    </form>
    </div>
</div>

{{-- Auto-select teacher JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const classroomSelect = document.getElementById('classroom_id');
        const teacherSelect = document.getElementById('teacher_id');

        classroomSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const teacherId = selectedOption.getAttribute('data-teacher');

            if (teacherId) {
                teacherSelect.value = teacherId;
            } else {
                teacherSelect.value = ""; // reset if no teacher
            }
        });
    });
</script>
@endsection
