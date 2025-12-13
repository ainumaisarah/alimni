@extends('layouts.app')

@section('content')
<h3 class="text-xl font-semibold mb-4">Assignments</h3>

@if($assignments->isEmpty())
    <p class="text-gray-600">No assignments uploaded yet.</p>
@else
    @foreach($assignments as $assignment)
        <div class="app-card mb-4">
            <h4 class="font-semibold text-lg">{{ $assignment->title }}</h4>
            <p>{{ $assignment->description }}</p>

            {{-- Due date --}}
            @if($assignment->due_at)
                <p class="text-sm text-gray-600">
                    Due: {{ \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur')->format('d M Y H:i') }}
                </p>
            @endif

            {{-- Assignment file download --}}
            @if($assignment->file)
                <p class="mt-1">
                    <a href="{{ route('student.assignments.download', $assignment->id) }}" class="btn-secondary">
                        Download Assignment File
                    </a>
                </p>
            @endif

            {{-- Submission Status --}}
            @php
                $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
            @endphp

            @if($submission)
                @php
                    $submittedAt = \Carbon\Carbon::parse($submission->submitted_at)->timezone('Asia/Kuala_Lumpur');
                    $dueAt = $assignment->due_at ? \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur') : null;
                @endphp

                <p>Submitted at: {{ $submittedAt->format('d M Y H:i') }}</p>

                @if($dueAt && $submittedAt->gt($dueAt))
                    <p class="error-alert font-semibold">Turned in Late</p>
                @else
                    <p class="success-alert font-semibold">Turned in On Time</p>
                @endif

                {{-- Previous submission --}}
                <p>
                    Previous submission:
                    <a href="{{ asset('storage/' . $submission->file) }}" class="text-blue-600 underline">
                        {{ basename($submission->file) }}
                    </a>
                </p>

                {{-- Delete submission --}}
                <form action="{{ route('student.assignments.deleteSubmission', $assignment->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger px-3 py-1 text-sm"
                        onclick="return confirm('Are you sure you want to delete this submission?');">
                        Delete Submission
                    </button>
                </form>

            @else
                <p class="font-semibold text-yellow-600 text-sm bg-yellow-100 px-2 py-1 rounded">
                    Not submitted yet
                </p>

            @endif

            {{-- Submission Form --}}
            <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                @csrf
                <label class="font-medium">Submit Your File</label>
                <input type="file" name="file" class="border rounded p-2 w-full mb-2" required>
                <button type="submit" class="btn-primary mt-1">Submit Assignment</button>
            </form>

        </div>
    @endforeach
@endif
@endsection
