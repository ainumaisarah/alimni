@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.quizzes', $quiz->classroom_id) }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                    bg-gray-100 hover:bg-gray-200 rounded-lg
                    text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="mb-4">Questions for: {{ $quiz->title }}</h2>
    </div>


    <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
       class="btn-primary px-4 py-2 text-sm">
       View Result
    </a>

    <a href="{{ route('teacher.questions.create', $quiz->id) }}"
       class="btn-primary px-4 py-2 rounded mb-4 inline-block">
       Add Question
    </a>

    @if($questions->count() > 0)
        <table class="min-w-full bg-white border rounded shadow overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Question</th>
                    <th class="px-4 py-2 border">Type</th>
                    <th class="px-4 py-2 border">Correct Answer</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $question->question_text }}</td>
                        <td class="px-4 py-2 border text-center">
                            {{ $question->question_type }} {{-- shows mcq or short --}}
                        </td>
                        <td class="px-4 py-2 border text-center">
                            {{ $question->question_type === 'mcq' ? $question->correct_answer : $question->short_answer }}
                        </td>

                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('teacher.questions.edit', [$quiz->id, $question->id]) }}"
                               class="btn-secondary px-4 py-2 text-sm">Edit</a>

                            <form action="{{ route('teacher.questions.destroy', [$quiz->id, $question->id]) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Delete this question?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
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
