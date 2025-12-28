@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto">

    <!-- Back Button -->
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
        <h2>Upload Material</h2>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>- {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="info-card p-4 border rounded">
        <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Hidden classroom_id --}}
            <input type="hidden" name="classroom_id" value="{{ $classroomId }}">

            {{-- Title --}}
            <div class="mb-3">
                <label class="font-semibold block mb-1">Title</label>
                <input type="text" name="title" class="w-full border p-2 rounded" required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="font-semibold block mb-1">Description</label>
                <textarea name="description" class="w-full border p-2 rounded" rows="3"></textarea>
            </div>

            {{-- Files --}}
            <div id="file-container" class="mb-3">
                <label class="font-semibold block mb-1">Upload Files</label>
                <div class="file-row mb-2 flex gap-2 items-center">
                    <input type="file" name="files[]">
                    <button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>
                </div>
            </div>
            <button type="button" onclick="addFileRow()" class="mb-3 btn-secondary">+ Add another file</button>

            {{-- Videos --}}
            <div id="video-container" class="mb-3">
                <label class="font-semibold block mb-1">Upload Videos</label>
                <div class="video-row mb-2 flex gap-2 items-center">
                    <input type="file" name="videos[]" accept="video/*">
                    <input type="text" name="video_links[]" placeholder="Or paste video link" class="border p-1 rounded w-full">
                    <button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>
                </div>
            </div>
            <button type="button" onclick="addVideoRow()" class="mb-3 btn-secondary">+ Add another video</button>

            <!--{{-- Folder Upload --}}
            <div class="mb-3">
                <label class="font-semibold block mb-1">Upload Folder</label>
                <input type="file" name="folders[]" webkitdirectory directory multiple class="border p-2 rounded w-full">
                <p class="text-sm text-gray-500 mt-1">Select a folder. All files inside will be uploaded.</p>
            </div>-->
            <br>
            <button type="submit" class="btn-primary mt-4">Upload Material</button>
        </form>
    </div>
</div>

<script>
function addFileRow() {
    let container = document.getElementById('file-container');
    let row = document.createElement('div');
    row.classList.add('file-row', 'mb-2', 'flex', 'gap-2', 'items-center');
    row.innerHTML = '<input type="file" name="files[]"><button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>';
    container.appendChild(row);
}

function addVideoRow() {
    let container = document.getElementById('video-container');
    let row = document.createElement('div');
    row.classList.add('video-row', 'mb-2', 'flex', 'gap-2', 'items-center');
    row.innerHTML = '<input type="file" name="videos[]" accept="video/*"><input type="text" name="video_links[]" placeholder="Or paste video link" class="border p-1 rounded w-full"><button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>';
    container.appendChild(row);
}

function removeRow(button) {
    button.parentElement.remove();
}
</script>
@endsection
