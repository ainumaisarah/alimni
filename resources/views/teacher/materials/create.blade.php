@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl">
    <h2 class="text-xl font-bold mb-4">Upload Material</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>@foreach ($errors->all() as $err)<li>- {{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Classroom</label>
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
        <label>Title</label>
        <input type="text" name="title" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="w-full border p-2 rounded" rows="4"></textarea>
    </div>

    <div class="mb-3">
        <label>File</label>
        <input type="file" name="file" class="w-full border p-2 rounded" required>
    </div>

    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded">Upload</button>
</form>

</div>
@endsection
