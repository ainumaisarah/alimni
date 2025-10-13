@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">Edit Classroom</h2>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST" class="space-y-4 max-w-lg">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium mb-1">Classroom Name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $classroom->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2"
                required
            >
        </div>

        <div>
            <label for="teacher_id" class="block font-medium mb-1">Assign Teacher</label>
            <select
                name="teacher_id"
                id="teacher_id"
                class="w-full border border-gray-300 rounded px-3 py-2"
            >
                <option value="">-- Select Teacher --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Classroom
            </button>
            <a href="{{ route('admin.classrooms.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
