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

@if(session('success'))
    <div class="succes-alert">
        {{ session('success') }}
    </div>
@endif

<div class="classbox">
<h3 style="font-size: 22px; font-weight: 650; color: #171818;">
    Assignments
</h3>

{{-- Teacher: Create new assignment --}}
@if(auth()->user()->role === 'teacher')
    <a href="{{ route('teacher.assignments.create', ['classroom_id' => $class->id]) }}"
       class="btn-primary mb-3 inline-block">Create New Assignment</a>
@endif

{{-- List assignments --}}
@if($assignments->count() > 0)
    @foreach($assignments as $assignment)
        <div class="app-card">
            <h3 class="font-semibold text-lg">
                {{ $assignment->title }}
            </h3>

            <p>{{ $assignment->description }}</p>

            @if($assignment->file)
                <p class="info-meta">
                    File:
                    @if(auth()->user()->role === 'teacher')
                        <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="text-blue-600 hover:underline">
                            {{ basename($assignment->file) }}
                        </a>
                    @else
                        <a href="{{ route('student.assignments.download', $assignment->id) }}" class="text-blue-600 hover:underline">
                            {{ basename($assignment->file) }}
                        </a>
                    @endif
                </p>
            @endif

            <p class="info-meta">Uploaded: {{ $assignment->created_at->format('d M Y') }}</p>

            {{-- Teacher: Edit/Delete buttons --}}
            @if($role === 'teacher')
                <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="btn-secondary inline-block px-4 py-2 text-sm">Edit</a>

                <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger px-4 py-2 text-sm"
                        onclick="return confirm('Are you sure you want to delete this assignment?');">
                        Delete
                    </button>
                </form>
            @endif

            {{-- Student: Submission form --}}
            @if($role === 'student')
                @php
                    $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
                @endphp

                <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    <input type="file" name="file" class="border rounded p-2 w-full mb-2" required>

                    @if($submission)
                        <p>Previous submission:
                            <a href="{{ asset('storage/' . $submission->file) }}" class="text-blue-600 underline">
                                {{ basename($submission->file) }}
                            </a>
                        </p>
                    @endif

                    <button type="submit" class="btn-primary mt-1">Submit Assignment</button>
                </form>
            @endif

        </div>
    @endforeach
@else
    <p class="empty-message">No assignments uploaded yet.</p>
@endif
</div>
@endsection
