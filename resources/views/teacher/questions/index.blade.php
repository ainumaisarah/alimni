@extends('layouts.app')

@section('content')
<div class="page-container">

    <h2 class="mb-4">Questions for: {{ $quiz->title }}</h2>

    <a href="{{ route('teacher.questions.create', $quiz->id) }}"
       class="btn-primary px-4 py-2 rounded mb-4 inline-block">
       Add Question
    </a>

    @if($questions->count() > 0)
        <table class="min-w-full bg-white border rounded shadow overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Question</th>
                    <th class="px-4 py-2 border">Correct Answer</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $question->question_text }}</td>
                        <td class="px-4 py-2 border text-center">{{ $question->correct_answer }}</td>
                        <td class="px-4 py-2 border text-center">
                            <form action="{{ route('teacher.questions.destroy', [$quiz->id, $question->id]) }}"
                                  method="POST" onsubmit="return confirm('Delete this question?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty-message mt-4">No questions added yet.</p>
    @endif
</div>
@endsection
