@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Enroll Student: {{ $student->name }}</h2>

    <form action="{{ route('admin.users.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="classroom_id" class="block font-medium mb-2">Select Classroom</label>
        <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2 mb-4">
            <option value="">-- No Classroom Assigned --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ $student->classroom_id == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>

        @error('classroom_id')
            <p class="error-alert mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary">
            Save Enrollment
        </button>
    </form>
</div>
@endsection
