@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto p-4">

    <h2 class="text-xl font-semibold mb-4">{{ $file->original_name }}</h2>

    @if($youtubeId)
        <iframe width="560" height="315"
                src="https://www.youtube.com/embed/{{ $youtubeId }}"
                title="{{ $file->original_name }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
        </iframe>
    @else
        <p>This is an external link. Click below to open:</p>
        <a href="{{ $file->link_url }}" target="_blank" class="btn-primary mt-2 inline-block">
            Open Link
        </a>
    @endif

</div>
@endsection
