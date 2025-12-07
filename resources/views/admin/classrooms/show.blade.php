@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>{{ $classroom->name }}</h2>

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

    {{--<h3 class="mt-4 font-semibold">Enroll Students</h3>  --}}
    <form action="{{ route('admin.classroom.enroll.submit', $classroom->id) }}" method="POST">
        @csrf

        <label for="students" class="block mb-1">Select Students:</label>
        <select name="students[]" id="students" multiple class="border border-gray-300 rounded p-2 w-full">
            @foreach(\App\Models\User::where('role', 'student')->get() as $student)
                <option value="{{ $student->id }}"
                    @if($classroom->students && $classroom->students->contains('id', $student->id)) selected @endif
                >
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
