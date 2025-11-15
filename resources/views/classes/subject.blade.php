@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">{{ $subject->name }}</h2>
    <p><strong>Teacher:</strong> {{ $subject->teacher->name ?? 'N/A' }}</p>

    {{-- Only teachers can upload materials --}}
    @if($role === 'Teacher')
        <a href="{{ route('teacher.materials.create', ['subject_id' => $subject->id, 'classroom_id' => $classroomId]) }}"
           class="btn btn-success mb-3">
            Upload Material
        </a>
    @endif

    <h4>Materials</h4>
    @if($subject->materials->count())
        <ul>
            @foreach($subject->materials as $material)
                <li>
                    <a href="{{ route('teacher.materials.download', $material->id) }}">
                        {{ $material->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No materials uploaded yet.</p>
    @endif

    <h4>Quizzes</h4>
    @if($subject->quizzes->count())
        <ul>
            @foreach($subject->quizzes as $quiz)
                <li>
                    <a href="{{ route($role === 'Teacher' ? 'teacher.quizzes.show' : 'student.quizzes.show', $quiz->id) }}">
                        {{ $quiz->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No quizzes assigned yet.</p>
    @endif
</div>
@endsection
