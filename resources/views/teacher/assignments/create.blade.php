@extends('layouts.app')

@section('content')
<div class = "page-container">
<h2>Create New Assignment</h2>

<div class="info-card">
<form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="hidden" name="classroom_id" value="{{ $classroom_id }}">

    <div>
        <label class="font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2"></textarea>
    </div>

    <div>
        <label class="font-medium">Upload File</label>
        <input type="file" name="file" class="w-full border rounded p-2">
    </div>

    <button type="submit" class="btn-primary">Create Assignment</button>
</form>
</div>
</div>
@endsection
