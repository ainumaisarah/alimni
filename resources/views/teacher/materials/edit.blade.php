@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto">

    <!-- Back Button -->
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.materials', $material->classroom_id) }}"
           class="h-8 w-8 inline-flex items-center justify-center
                  bg-gray-100 hover:bg-gray-200 rounded-lg">
            ←
        </a>
        <h2 class="text-xl font-semibold">Edit Material</h2>
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
        <form action="{{ route('teacher.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-3">
                <label class="font-semibold block mb-1">Title</label>
                <input type="text" name="title" class="w-full border p-2 rounded" value="{{ $material->title }}" required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="font-semibold block mb-1">Description</label>
                <textarea name="description" class="w-full border p-2 rounded" rows="3">{{ $material->description }}</textarea>
            </div>

            {{-- Existing Files --}}
            @if($material->files->count() > 0)
                <div class="mb-3">
                    <label class="font-semibold block mb-2">Existing Files / Videos / Links</label>
                    <ul class="list-disc pl-5">
                        @foreach($material->files as $file)
                            <li class="mb-2 flex items-center gap-2">
                                @if($file->file_type === 'link')
                                    <span class="text-gray-600">{{ $file->original_name }} (Link)</span>
                                @else
                                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $file->original_name }}
                                    </a>
                                @endif
                                {{-- Delete file --}}
                                <form action="{{ route('teacher.materials.file.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger text-sm">Delete</button>
                                </form>

                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- New Files --}}
            <div id="file-container" class="mb-3">
                <label class="font-semibold block mb-1">Add New Files</label>
                <div class="file-row mb-2 flex gap-2 items-center">
                    <input type="file" name="files[]">
                    <button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>
                </div>
            </div>
            <button type="button" onclick="addFileRow()" class="mb-3 btn-secondary">+ Add another file</button>

            {{-- New Videos / Links --}}
            <div id="video-container" class="mb-3">
                <label class="font-semibold block mb-1">Add New Videos / Links</label>
                <div class="video-row mb-2 flex gap-2 items-center">
                    <input type="file" name="videos[]" accept="video/*">
                    <input type="text" name="video_links[]" placeholder="Or paste video link" class="border p-1 rounded w-full">
                    <button type="button" onclick="removeRow(this)" class="btn-danger">Remove</button>
                </div>
            </div>
            <button type="button" onclick="addVideoRow()" class="mb-3 btn-secondary">+ Add another video</button>

            <button type="submit" class="btn-primary mt-4">Update Material</button>
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
