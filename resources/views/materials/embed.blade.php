@extends('layouts.app')

@section('content')
<div class="page-container max-w-2xl mx-auto p-4">
    <h2 class="font-semibold text-lg mb-4">{{ $name }}</h2>

    @if(Str::contains($url, 'youtube.com') || Str::contains($url, 'youtu.be'))
        @php
            // Convert to embed URL
            if(Str::contains($url, 'watch?v=')) {
                $embedUrl = str_replace('watch?v=', 'embed/', $url);
            } else {
                $embedUrl = $url;
            }
        @endphp
        <iframe width="100%" height="400" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
    @else
        <p>This link cannot be embedded. <a href="{{ $url }}" target="_blank">Click here to open</a></p>
    @endif
</div>
@endsection
