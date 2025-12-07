@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Edit Classroom</h2>

    @if ($errors->any())
        <div class="error-alert mb-4">
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

        <div class="app-card">
            <label for="name">Classroom Name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $classroom->name) }}"
                required
            >

            <label for="teacher_id">Assign Teacher</label>
            <select
                name="teacher_id"
                id="teacher_id"
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
            <button type="submit" class="btn-primary">
                Update Classroom
            </button>
            <a href="{{ route('admin.classrooms.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
