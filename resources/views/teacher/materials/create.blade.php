@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto">
<div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.materials', $classroomId) }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                    bg-gray-100 hover:bg-gray-200 rounded-lg
                    text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="mb-4">Upload Material</h2>
    </div>


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
