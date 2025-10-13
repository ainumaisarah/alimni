@extends('layouts.app')

@section('content')
<div class="p-6 max-w-lg mx-auto">
    <h2 class="text-xl font-semibold mb-6">Create New Classroom</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium mb-1">Classroom Name</label>
            <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="teacher_id" class="block font-medium mb-1">Assign Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select Teacher --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-blue px-4 py-2 rounded hover:bg-blue-700">Create Classroom</button>
    </form>
</div>
@endsection
