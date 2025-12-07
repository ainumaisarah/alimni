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

            @if($assignment->file)
                <p class="mt-1">
                    <a href="{{ route('student.assignments.download', $assignment->id) }}" class="btn-secondary">Download Assignment File</a>
                </p>
            @endif

            {{-- Submission Form --}}
            @php
                $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
            @endphp

            <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                @csrf
                <label class="font-medium">Submit Your File</label>
                <input type="file" name="file" class="border rounded p-2 w-full mb-2" required>

                @if($submission)
                    <p>Previous submission: <a href="{{ asset('storage/' . $submission->file) }}" class="text-blue-600 underline">{{ basename($submission->file) }}</a></p>
                @endif

                <button type="submit" class="btn-primary mt-1">Submit Assignment</button>
            </form>
        </div>
    @endforeach
@endif
@endsection
