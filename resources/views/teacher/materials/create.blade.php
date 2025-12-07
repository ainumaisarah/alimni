@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto">

    <h2 class="mb-4">Upload Material</h2>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>- {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="info-card">
        <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="font-semibold mb-1 block">Classroom</label>
                <select name="classroom_id" class="w-full border p-2 rounded" required>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}"
                            @if(isset($classroomId) && $classroomId == $classroom->id) selected @endif>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="font-semibold mb-1 block">Title</label>
                <input type="text" name="title" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="font-semibold mb-1 block">Description</label>
                <textarea name="description" class="w-full border p-2 rounded" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label class="font-semibold mb-1 block">File</label>
                <input type="file" name="file" class="w-full border p-2 rounded" required>
            </div>

            <button type="submit" class="btn-primary">Upload</button>
        </form>
    </div>

</div>
@endsection
