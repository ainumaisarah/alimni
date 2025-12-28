@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.classrooms.index') }}"
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
        <h2>{{ $classroom->name }}</h2>
    </div>

    <p><strong>Teacher:</strong> {{ $classroom->teacher->name ?? 'N/A' }}</p>
    <div class = "mt-4 app-card">
    <h3>Students:</h3>
    @if($classroom->students && $classroom->students->count())
        <ul class="list-disc pl-5 mt-2">
            @foreach($classroom->students as $student)
                <li>{{ $student->name }} ({{ $student->username }})</li>
            @endforeach
        </ul>
    @else
        <p>No students enrolled in this class.</p>
    @endif
<br>
    <form action="{{ route('admin.classroom.enroll.submit', $classroom->id) }}" method="POST">
        @csrf

        <label for="students" class="block mb-1">Select Students:</label>
        <select name="students[]" id="students" multiple class="border border-gray-300 rounded p-2 w-full">
            @foreach(\App\Models\User::where('role', 'student')
                ->whereDoesntHave('classrooms', function($q) use ($classroom) {
                    $q->where('classrooms.id', $classroom->id);
                })
                ->get() as $student)
                <option value="{{ $student->id }}">
                    {{ $student->name }} ({{ $student->username }})
                </option>
            @endforeach
        </select>
        </div>
        <button type="submit" class="btn-primary">
            Enroll Selected Students
        </button>
    </form>

    <a href="{{ route('admin.classrooms.index') }}" class="btn-secondary">
        Back to Classes
    </a>
</div>
@endsection
