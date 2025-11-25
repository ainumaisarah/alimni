@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Class: {{ $class->name }}</h2>
    {{-- Materials --}}
    <h3 class="text-xl font-semibold mb-2">Materials</h3>
    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.materials.create', ['classroom_id' => $class->id]) }}" class="btn btn-primary">Upload New Material</a>
    @endif
    @if($materials->count() > 0)
        @foreach($materials as $material)
            <div class="mb-4 border p-4 rounded shadow">
                <p class="font-medium">{{ $material->title }}</p>
                <a href="{{ route('student.materials.download', $material->id) }}"
                   class="bg-blue-500 text-black px-3 py-1 rounded">Download</a>

                @if($role === 'teacher')
                    <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-red px-3 py-1 rounded">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-gray-500 mb-6">No materials uploaded yet.</p>
    @endif

    {{-- Quizzes --}}
    <h3 class="text-xl font-semibold mb-2">Quizzes</h3>
    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.quizzes.create', ['classroom_id' => $class->id]) }}" class="btn btn-success">Create New Quiz</a>
    @endif
    @if($quizzes->count() > 0)
        @foreach($quizzes as $quiz)
            @php
                $result = $quiz->results()->where('student_id', auth()->id())->latest()->first();
                $totalQuestions = $quiz->questions->count();
                $answeredQuestions = $result ? count($result->answers ?? []) : 0;
                $canRetake = (!$result) || ($answeredQuestions < $totalQuestions);
            @endphp

            <div class="mb-6 border p-4 rounded shadow">
                <h3 class="text-xl font-semibold mb-2">{{ $quiz->title }}</h3>
                <p class="mb-2">Questions: {{ $totalQuestions }}</p>

                @if(auth()->user()->role === 'student')
                    @if($canRetake)
                        <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                           class="bg-blue-500 text-black px-3 py-1 rounded">Take Quiz</a>
                    @else
                        <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                           class="bg-yellow-400 text-black px-3 py-1 rounded">Review Quiz</a>
                        <span class="text-green-600 ml-2">Score: {{ $result->score }}/{{ $totalQuestions }}</span>
                    @endif
                @elseif(auth()->user()->role === 'teacher')
                    <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                       class="bg-green-500 text-black px-3 py-1 rounded">Edit Quiz</a>
                       <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
                        class="bg-purple-500 text-black px-3 py-1 rounded ml-2">View Results</a>
                    <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-red px-3 py-1 rounded ml-2"
                            onclick="return confirm('Are you sure you want to delete this quiz?');">
                            Delete Quiz
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-gray-500">No quizzes available for this class yet.</p>
    @endif

</div>
@endsection
