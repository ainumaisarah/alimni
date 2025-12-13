@extends('layouts.app')

@section('content')
<div class="page-container p-6">
    <h2 class="mb-6 font-semibold text-2xl">{{ $quiz->title }}</h2>
    <p class="mb-4">{{ $quiz->description }}</p>

    @if($attemptsCount == 0)
        <p>Please attempt the quiz.</p>
        <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-primary">Attempt Quiz</a>
    @else
        <table class="w-full text-left border mb-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Attempt</th>
                    <th class="p-2 border">Score</th>
                    <th class="p-2 border">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $attempt)
                    <tr>
                        <td class="p-2 border">{{ $attempt->attempt_number }}</td>
                        <td class="p-2 border">{{ $attempt->score }}%</td>
                        <td class="p-2 border">
                            <a href="{{ route('student.quizzes.review', [$quiz->id, $attempt->id]) }}" class="text-blue-600 underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($attemptsCount < $maxAttempts)
            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-primary mt-2 inline-block">Attempt Again</a>
            <p class="mt-1 text-sm text-gray-500">
                You can attempt {{ $maxAttempts - $attemptsCount }} more time(s).
            </p>
        @else
            <p class="mt-2 text-red-600 font-semibold">
                You have reached the maximum attempts ({{ $maxAttempts }}).
            </p>
        @endif
    @endif {{-- <-- Close the very first @if --}}
</div>
@endsection
