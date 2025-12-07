@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>My Quizzes</h2>

    @if(session('success'))
        <div class="success-alert mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($quizzes->count() > 0)
        <table>
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Classroom</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)

                @php
                    // Student's latest result
                    $result = $quiz->results()
                        ->where('student_id', auth()->id())
                        ->latest()
                        ->first();

                    // Determine total current questions
                    $currentTotal = $quiz->questions->count();

                    // Determine answered question count (only from latest attempt)
                    $answeredTotal = $result ? count($result->answers ?? []) : 0;

                    // Determine if teacher updated quiz (student has not answered all)
                    $needsRetake = (!$result) || ($answeredTotal < $currentTotal);
                @endphp

                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>{{ $quiz->classroom->name ?? 'N/A' }}</td>
                    <td>

                        {{-- Student must retake because teacher added questions --}}
                        @if($needsRetake)
                            <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                               class="btn-primary">
                                Take Quiz
                            </a>

                        {{-- Student already completed latest version — show review --}}
                        @else
                            <a href="{{ route('student.quizzes.show', $quiz->id) }}"
                               class="btn-secondary">
                                Review Quiz
                            </a>
                            <span class="success-alert mb-4">
                                Score: {{ $result->score }}/{{ $quiz->questions->count() }}
                            </span>
                        @endif

                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty-message">No quizzes available for your class yet.</p>
    @endif
</div>
@endsection
