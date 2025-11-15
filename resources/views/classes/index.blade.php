@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">My Classes</h2>

    @if ($role === 'Teacher')
        @foreach ($classes as $class)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $class->name }}</h5>
                    <p class="card-text"><strong>Subjects:</strong></p>
                    <ul>
                        @foreach ($class->subjects as $subject)
                            <li class="mb-2">
                                <!-- Link to subject page -->
                                <a href="{{ route('teacher.classes.subject.show', $subject->id) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $subject->name }}
                                </a>

                                <!-- Upload material link -->
                                <a href="{{ route('teacher.materials.create', [
                                        'subject_id' => $subject->id,
                                        'classroom_id' => $class->id
                                    ]) }}"
                                   class="ml-3 text-green-600 hover:underline">
                                    Upload Material
                                </a>

                                <!-- List existing materials -->
                                @if ($subject->materials->count() > 0)
                                    <ul class="mt-1 ml-5 list-disc">
                                        @foreach ($subject->materials as $material)
                                            <li>
                                                <a href="{{ route('teacher.materials.download', $material->id) }}"
                                                   class="text-gray-700 hover:text-gray-900">
                                                    {{ $material->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

    @elseif ($role === 'Student' && $class)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $class->name }}</h5>
                <p class="card-text"><strong>Subjects:</strong></p>
                <ul>
                    @foreach ($class->subjects as $subject)
                        <li class="mb-2">
                            <a href="{{ route('student.classes.subject.show', $subject->id) }}"
                               class="text-blue-600 hover:underline">
                                {{ $subject->name }}
                            </a>
                            <small> — Taught by {{ $subject->teacher->name ?? 'N/A' }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <p>No classes found.</p>
    @endif
</div>
@endsection
