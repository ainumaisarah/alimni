@extends('classes.show')

@section('content')
<div class="class-container">
<nav class="class-nav">
        <h2><a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
            {{ $class->name }}
        </a></h2>
        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}"
                class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}"> Materials </a>
             <a href="{{ route('classes.assignment', $class->id) }}"
                class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}"> Assignment </a>
            <a href="{{ route('classes.quizzes', $class->id) }}"
                class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}"> Quiz </a>
        </div>
</nav>

<div class="classbox">
{{-- MATERIALS SECTION (your original code continues below) --}}
    <h3 style="font-size: 22px; font-weight: 650; color: #2b5948;">
        Materials
    </h3>

    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.materials.create', ['classroom_id' => $class->id]) }}"
           class="btn-primary mb-3 inline-block">Upload New Material</a>
    @endif

    @if($materials->count() > 0)
        @foreach($materials as $material)
            <div class="app-card">

                <h3 class="font-semibold text-lg">
                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('student.materials.download', $material->id) }}">
                            {{ $material->title }}
                        </a>
                    @else
                        {{ $material->title }}
                    @endif
                </h3>

                    <p>{{ $material->description }}</p>

                <p class="info-meta">Uploaded: {{ $material->created_at->format('d M Y') }}</p>


                @if($role === 'teacher')
                    <form action="{{ route('teacher.materials.destroy', $material->id) }}"
                          method="POST"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('teacher.materials.view', $material->id) }}"
                    class="btn-secondary" target="_blank" rel="noopener">
                    View
                    </a>

                @endif
            </div>
        @endforeach
    @else
        <p class="empty-message">No materials uploaded yet.</p>
    @endif
    </div>
</div>
@endsection
