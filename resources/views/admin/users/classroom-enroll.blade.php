@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Enroll Students to Classroom</h2>

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

                <button type="submit" class="btn-primry">
                    Enroll Selected Students
                </button>
            </form>
        </div>
    @endforeach
</div>
@endsection
