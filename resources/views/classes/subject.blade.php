@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">{{ $subject->name }}</h2>
    <p><strong>Taught by:</strong> {{ $subject->teacher->name ?? 'N/A' }}</p>

    {{-- Upload Material button for teachers --}}
    @if($role === 'Teacher')
        @if($subject->classroom_id)
            <a href="{{ route('teacher.materials.create', [
                                        'subject_id' => $subject->id,
                                        'classroom_id' => $class->id
                                    ]) }}"
                                   class="ml-3 text-green-600 hover:underline">
                                    Upload Material
                                </a>
        @else
            <p class="text-red-500">This subject is not assigned to a class yet. Cannot upload materials.</p>
        @endif
    @endif

    <!-- Upload material link -->



    <hr class="my-3">

    {{-- Materials list --}}
    <h4>Materials</h4>
    @if($subject->materials->count() > 0)
        <ul class="list-disc list-inside mb-3">
            @foreach($subject->materials as $material)
                <li>
                    <a href="{{ route('teacher.materials.download', $material->id) }}"
                       class="text-blue-600 hover:underline">
                        {{ $material->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No materials uploaded yet.</p>
    @endif

    {{-- Quizzes list --}}
    <h4>Quizzes</h4>
    @if($subject->quizzes->count() > 0)
        <ul class="list-disc list-inside">
            @foreach($subject->quizzes as $quiz)
                <li>
                    <a href="{{ $role === 'Teacher'
                                ? route('teacher.quizzes.show', $quiz->id)
                                : route('student.quizzes.show', $quiz->id) }}"
                       class="text-blue-600 hover:underline">
                        {{ $quiz->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No quizzes assigned yet.</p>
    @endif
</div>
@endsection
