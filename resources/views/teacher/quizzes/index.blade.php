@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Questions for: {{ $quiz->title }}</h2>
    <a href="{{ route('teacher.questions.create', $quiz->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Question</a>

    <table class="min-w-full mt-4 bg-white border">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Question</th>
                <th class="px-4 py-2 border">Correct Answer</th>
                <th class="px-4 py-2 border">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td class="px-4 py-2 border">{{ $question->question_text }}</td>
                <td class="px-4 py-2 border">{{ $question->correct_answer }}</td>
                <td class="px-4 py-2 border">
                    <form action="{{ route('teacher.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
