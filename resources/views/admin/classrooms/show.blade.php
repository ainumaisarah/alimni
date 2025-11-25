@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">{{ $classroom->name }}</h2>

    <p><strong>Teacher:</strong> {{ $classroom->teacher->name ?? 'N/A' }}</p>

    <h3 class="mt-4 font-semibold">Students:</h3>
    @if($classroom->students && $classroom->students->count())
        <ul class="list-disc pl-5 mt-2">
            @foreach($classroom->students as $student)
                <li>{{ $student->name }} ({{ $student->username }})</li>
            @endforeach
        </ul>
    @else
        <p>No students enrolled in this class.</p>
    @endif

    <h3 class="mt-6 font-semibold">Enroll Students</h3>
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

        <button type="submit" class="mt-3 bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
            Enroll Selected Students
        </button>
    </form>

    <a href="{{ route('admin.classrooms.index') }}" class="mt-4 inline-block bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
        Back to Classes
    </a>
</div>
@endsection
