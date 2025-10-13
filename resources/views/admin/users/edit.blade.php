@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Enroll Student: {{ $student->name }}</h2>

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
            <p class="text-red-600 mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Save Enrollment
        </button>
    </form>
</div>
@endsection
