@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Enroll Students to Classroom</h2>

    @foreach ($classrooms as $classroom)
        <div class="mb-6 p-4 border rounded">
            <h3 class="text-lg font-semibold mb-2">{{ $classroom->name }}</h3>

            <form action="{{ route('admin.classroom.enroll.submit', $classroom->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach ($students as $student)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                {{ $student->classroom_id == $classroom->id ? 'checked' : '' }}>
                            <span>{{ $student->name }} ({{ $student->username }})</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="mt-4 bg-blue-600 text-blue px-4 py-2 rounded hover:bg-blue-700">
                    Enroll Selected Students
                </button>
            </form>
        </div>
    @endforeach
</div>
@endsection
