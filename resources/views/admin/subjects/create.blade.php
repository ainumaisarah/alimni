@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Add New Subject</h1>

    <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Subject Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="teacher_id" class="form-label">Assign Teacher (optional)</label>
            <select name="teacher_id" id="teacher_id" class="form-select">
                <option value="">-- None --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Subject</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
