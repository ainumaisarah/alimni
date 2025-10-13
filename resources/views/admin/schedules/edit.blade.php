@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Edit Schedule</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="classroom_id" class="block font-medium mb-1">Classroom</label>
            <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled>Select a classroom</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}"
                        {{ (old('classroom_id', $schedule->classroom_id) == $classroom->id) ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="teacher_id" class="block font-medium mb-1">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled>Select a teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"
                        {{ (old('teacher_id', $schedule->teacher_id) == $teacher->id) ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="subject" class="block font-medium mb-1">Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject', $schedule->subject) }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="day" class="block font-medium mb-1">Day</label>
            <select name="day" id="day" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled>Select a day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" {{ (old('day', $schedule->day) == $day) ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <input type="time" name="start_time" id="start_time"
            value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}"
            class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <input type="time" name="end_time" id="end_time"
            value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}"
            class="w-full border rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500">Update Schedule</button>
    </form>
</div>
@endsection
