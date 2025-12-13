@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2 class="mb-4">Quiz Results Summary: {{ $quiz->title }}</h2>

    @if($studentResults->isEmpty())
        <p class="empty-message">No students in this class.</p>
    @else
        <table class="w-full border text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Student</th>
                    <th class="p-2 border">Total Attempts</th>
                    <th class="p-2 border">Latest Score</th>
                    <th class="p-2 border">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentResults as $res)
                    <tr>
                        <td class="p-2 border">{{ $res['student']->name }}</td>
                        <td class="p-2 border">{{ $res['totalAttempts'] }}</td>
                        <td class="p-2 border">
                            @if($res['latestScore'] !== null)
                                {{ $res['latestScore'] }}%
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="p-2 border">
                            @if($res['totalAttempts'] > 0)
                                <select onchange="if(this.value) window.location.href=this.value">
                                    <option value="">Select Attempt</option>
                                    @foreach($res['attempts'] as $attempt)
                                        <option value="{{ route('teacher.quizzes.review', [$quiz->id, $attempt->id]) }}">
                                            Attempt #{{ $attempt->attempt_number }} ({{ $attempt->score }}%)
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
