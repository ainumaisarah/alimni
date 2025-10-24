@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Subject</h1>

    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Subject Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $subject->name }}" required>
        </div>

        <div class="mb-3">
            <label for="teacher_id" class="form-label">Assign Teacher (optional)</label>
            <select name="teacher_id" id="teacher_id" class="form-select">
                <option value="">-- None --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ $subject->teacher_id == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Subject</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
