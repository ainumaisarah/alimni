@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">My Quizzes</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($quizzes->count() > 0)
        <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Subject</th>
                    <th class="px-4 py-2 border">Classroom</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                @php
                    // Get latest attempt by this student
                    $result = $quiz->results()->where('student_id', auth()->id())->latest()->first();
                @endphp
                <tr>
                    <td class="px-4 py-2 border">{{ $quiz->title }}</td>
                    <td class="px-4 py-2 border">{{ $quiz->subject->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border">{{ $quiz->classroom->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border">
                        @if($result)
                            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="bg-yellow-400 text-black px-3 py-1 rounded">Review Quiz</a>
                            <span class="text-green-600 ml-2">Score: {{ $result->score }}/{{ $quiz->questions->count() }}</span>
                        @else
                            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Take Quiz</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="mt-4 text-gray-500">No quizzes available for your class yet.</p>
    @endif
</div>
@endsection
