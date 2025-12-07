@extends('layouts.app')

@section('content')
<div class="page-container">

    <h2 class="mb-4">My Quizzes</h2>

    <a href="{{ route('teacher.quizzes.create') }}"
       class="btn-primary px-4 py-2 rounded mb-4 inline-block">
       Create New Quiz
    </a>

    @if($quizzes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Subject</th>
                    <th class="px-4 py-2 border">Classroom</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                    <tr>
                        <td class="px-4 py-2 border">{{ $quiz->title }}</td>
                        <td class="px-4 py-2 border">{{ $quiz->subject->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $quiz->classroom->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <a href="{{ route('teacher.questions.index', $quiz->id) }}"
                               class="btn-primary px-3 py-1 rounded">
                               View
                            </a>
                            <a href="{{ route('teacher.quizzes.edit', $quiz->id) }}"
                               class="btn-secondary px-3 py-1 rounded">
                               Edit
                            </a>
                            <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this quiz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty-message mt-4">You have not created any quizzes yet.</p>
    @endif
</div>
@endsection
