@extends('classes.show')

@section('content')
<div class="class-container">

    <!-- Navigation -->
    <nav class="class-nav">
        <h2>
            <a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
                {{ $class->name }}
            </a>
        </h2>
        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}" class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}"> Materials </a>
            <a href="{{ route('classes.assignment', $class->id) }}" class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}"> Assignment </a>
            <a href="{{ route('classes.quizzes', $class->id) }}" class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}"> Quiz </a>
        </div>
    </nav>

    <div class="classbox">
        <h3 style="font-size: 22px; font-weight: 650; color: #2b5948;">Materials</h3>

        @if(auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.materials.create', ['classroom_id' => $class->id]) }}" class="btn-primary mb-3 inline-block">Upload New Material</a>
        @endif

        @if($materials->count() > 0)
            @foreach($materials as $material)
                <div class="app-card mb-6 p-4 border rounded shadow-sm">

                    <h3 class="font-semibold text-lg mb-1">{{ $material->title }}</h3>
                    <p class="mb-3">{{ $material->description }}</p>

                    @php
                        $grouped = $material->files->groupBy('folder');
                    @endphp

                    @foreach($grouped as $folder => $files)
                        <h4 class="font-semibold mb-2">{{ $folder ?? 'No Folder' }}</h4>
                        <ul class="pl-5">
                            @foreach($files as $file)
                                <li class="mb-2 flex-col gap-2">

                                    {{-- File / Video Download --}}
                                    @if($file->file_type !== 'link')
                                        <a href="{{ route('materials.download', $file->id) }}" class="text-blue-600 hover:underline">
                                            {{ $file->original_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-600">{{ $file->original_name }} (Link)</span>
                                    @endif

                                    {{-- View / Watch --}}
                                    @if($file->file_type === 'video' && $file->file_path)
                                        <video width="480" controls class="rounded border mt-1">
                                            <source src="{{ asset('storage/'.$file->file_path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($file->file_type === 'link' && $file->link_url)
                                        @php
                                            $youtubeId = null;
                                            if (Str::contains($file->link_url, 'youtube.com/watch?v=')) {
                                                parse_str(parse_url($file->link_url, PHP_URL_QUERY), $query);
                                                $youtubeId = $query['v'] ?? null;
                                            } elseif (Str::contains($file->link_url, 'youtu.be/')) {
                                                $youtubeId = last(explode('/', $file->link_url));
                                            }
                                        @endphp
                                        @if($youtubeId)
                                            <iframe width="480" height="270" class="rounded border mt-1"
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                                title="{{ $file->original_name }}"
                                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <a href="{{ $file->link_url }}" target="_blank" class="btn-secondary">Watch</a>
                                        @endif
                                    @elseif(in_array($file->file_type, ['pdf', 'image']) && $file->file_path)
                                        <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="btn-secondary inline-block px-2 py-2 mt-1">View</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        {{-- Download All: Only for multiple physical files/videos --}}
                        @php
                            $physicalFiles = $files->filter(fn($f) => $f->file_type !== 'link');
                        @endphp
                        @if($physicalFiles->count() > 1)
                            <p class = "font-semibold text-white">
                                <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('materials.downloadAll', [$material->id, $folder]) }}" class="btn-primary">
                                    Download All
                                </a>
                            </p>

                        @endif
                    @endforeach

                    @if(auth()->user()->role === 'teacher')
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('teacher.materials.edit', $material->id) }}" class="btn-secondary">Edit</a>
                            <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Delete this material?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    @endif

                </div>
            @endforeach
        @else
            <p class="empty-message">No materials uploaded yet.</p>
        @endif

    </div>
</div>
@endsection
