@extends('layouts.app')

@section('content')
<div class="page-container p-6">
<h2>Edit Assignment</h2>

<form action="{{ route('teacher.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="info-card mb-6">
    @csrf
    @method('PUT')

    <div>
        <label class="font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded p-2" value="{{ $assignment->title }}" required>
    </div>

    <div>
        <label class="font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2">{{ $assignment->description }}</textarea>
    </div>

    <div>
    <label class="font-medium">Due</label>
    <input type="datetime-local"
       name="due_at"
       value="{{ old('due_at', optional($assignment)->due_at ? \Carbon\Carbon::parse($assignment->due_at)->format('Y-m-d\TH:i') : '') }}"
       class="w-full border rounded p-2">
    </div>


    <div>
        <label class="font-medium">Upload New File</label>
        <input type="file" name="file" class="w-full border rounded p-2">
        @if($assignment->file)
            <p class="mt-2">Current file: <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="text-blue-600 underline">{{ basename($assignment->file) }}</a></p>
        @endif
    </div>

    <button type="submit" class="btn-primary">Update Assignment</button>
</form>

<form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" class="mt-4">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger" onclick="return confirm('Are you sure?')">Delete Assignment</button>
</form>
</div>
@endsection
