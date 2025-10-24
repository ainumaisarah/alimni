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

        <div class="mb-4">
            <label class="block mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Classroom</label>
            <select name="classroom_id" class="w-full border rounded p-2" required>
                <option value="">-- Select Classroom --</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1">File (optional)</label>
            <input type="file" name="file" class="w-full">
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded">Upload</button>
        <a href="{{ route('teacher.materials.index') }}" class="ml-2">Cancel</a>
    </form>
</div>
@endsection
