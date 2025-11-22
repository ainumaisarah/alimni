@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    <h2 class="text-2xl font-bold mb-4">{{ $class->name }} Materials & Quizzes</h2>

    {{-- TEACHER BUTTONS (top) --}}
    @if($role === 'teacher')
        <div class="mb-4 flex space-x-2">
            {{-- create uses query param classroom_id so no route parameter required --}}
            <a href="{{ route('teacher.materials.create', ['classroom_id' => $class->id]) }}"
               class="bg-blue-500 text-black px-4 py-2 rounded">
               Upload / Manage Materials
            </a>

            <a href="{{ route('teacher.quizzes.create', ['classroom_id' => $class->id]) }}"
               class="bg-green-500 text-black px-4 py-2 rounded">
               Create New Quiz
            </a>
        </div>
    @endif
    {{-- END TEACHER BUTTONS --}}

    <h3 class="text-xl font-semibold mb-2">Materials</h3>
    @if($materials->count() > 0)
        <ul class="mb-4">
            @foreach($materials as $material)
                <li>
                    {{-- use the download route name you see in php artisan route:list (see STEP 0) --}}
                    <a href="{{ route('teacher.materials.download', $material->id) }}">
                        {{ $material->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 mb-4">No materials uploaded yet.</p>
    @endif

    <h3 class="text-xl font-semibold mb-2">Quizzes</h3>
      @if($quizzes->count() > 0)
    <ul>
        @foreach($quizzes as $quiz)
            <li>
                @if($role === 'teacher')
                    <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}" class="text-blue-600">
                        {{ $quiz->title }} (Edit)
                    </a>
                @else
                    @if($quiz->result)
                        {{ $quiz->title }} - Score: {{ $quiz->result->score }}
                    @else
                        <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="text-blue-600">
                            {{ $quiz->title }} (Take Quiz)
                        </a>
                    @endif
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p class="text-gray-500">No quizzes created yet.</p>
@endif


</div>
@endsection
