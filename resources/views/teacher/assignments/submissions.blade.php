@extends('layouts.app')

@section('content')
<div class = "schedule-container">
    <a href="{{ route('classes.assignment', $assignment->classroom_id) }}" class="btn-primary">Back</a>
<h3>Submissions for: {{ $assignment->title }}</h3>

<p class="mb-4">{{ $assignment->description }}</p>

@if($assignment->submissions->count() > 0 || $students->count() > 0)
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Student Name</th>
                <th class="border border-gray-300 px-4 py-2">Submission File</th>
                <th class="border border-gray-300 px-4 py-2">Submitted At</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $submission = $assignment->submissions->where('student_id', $student->id)->first();
                    $dueAt = $assignment->due_at ? \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur') : null;
                    if($submission) {
                        $submittedAt = \Carbon\Carbon::parse($submission->submitted_at)->timezone('Asia/Kuala_Lumpur');
                        $status = ($dueAt && $submittedAt->gt($dueAt)) ? 'late' : 'on-time';
                    } else {
                        $status = 'not-submitted';
                        $submittedAt = null;
                    }
                @endphp
                <tr class="
                    @if($status === 'on-time') bg-green-200
                    @elseif($status === 'late') bg-red-200
                    @elseif($status === 'not-submitted') bg-white
                    @endif
                ">
                    <td class="border border-gray-300 px-4 py-2">{{ $student->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if($submission)
                            <a href="{{ asset('storage/' . $submission->file) }}" class="text-blue-600 underline">
                                {{ basename($submission->file) }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $submittedAt ? $submittedAt->format('d M Y H:i') : '-' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2 font-semibold text-center">
                        @if($status === 'on-time') Turned in On Time
                        @elseif($status === 'late') Turned in Late
                        @else Not Submitted
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No students or submissions found.</p>
@endif

</div>
@endsection
