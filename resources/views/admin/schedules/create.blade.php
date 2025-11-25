@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Create Schedule</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
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
            <label for="classroom_id" class="block font-medium mb-1">Classroom</label>
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
            <label for="teacher_id" class="block font-medium mb-1">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="day" class="block font-medium mb-1">Day</label>
            <select name="day" id="day" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="start_time" class="block font-medium mb-1">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="end_time" class="block font-medium mb-1">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Create Schedule</button>
    </form>
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
