@extends('classes.show')

@section('content')
<div class="class-container">
    <nav class="class-nav">
            <div class="flex items-center gap-2">
            <a href="{{ route('classes.index') }}" :active="request()->routeIs('classes.index')"
                class="h-8 w-8 inline-flex items-center justify-center p-2" style="color: rgb(224, 216, 191);
                hover:text-[#1f4033]">
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
            <h2><a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
            {{ $class->name }}
        </a></h2>
        </div>

        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}"
               class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}">Materials</a>
            <a href="{{ route('classes.assignment', $class->id) }}"
               class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}">Assignment</a>
            <a href="{{ route('classes.quizzes', $class->id) }}"
               class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}">Quiz</a>
        </div>
    </nav>

    @if(session('success'))
        <div class="succes-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="classbox">
        <h3 style="font-size: 22px; font-weight: 650; color: #2b5948;">
            Assignments
        </h3>

        @if(auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.assignments.create', ['classroom_id' => $class->id]) }}"
               class="btn-primary mb-3 inline-block">Create New Assignment</a>
        @endif

        @if($assignments->count() > 0)
            @foreach($assignments as $assignment)
                <div class="app-card mb-4">

                    {{-- Assignment title --}}
                    @if($role === 'teacher')
                        <h3 class="font-semibold text-lg">
                            <a href="{{ route('teacher.assignments.submissions', ['assignment' => $assignment->id]) }}" class="text-blue-600 hover:underline">
                                {{ $assignment->title }}
                            </a>
                        </h3>
                    @else
                        <h3 class="font-semibold text-lg">{{ $assignment->title }}</h3>
                    @endif

                    <h5 class="font-semibold text-sm text-gray-900">{{ $assignment->description }}</h5>



                    @if($assignment->file)
                        <p class="font-semibold text-sm !text-gray-900">
                        <a href="{{ auth()->user()->role === 'teacher'
                            ? route('teacher.assignments.download', $assignment->id)
                            : route('student.assignments.download', $assignment->id) }}"
                        class="text-blue-200 hover:underline">
                            {{ basename($assignment->file) }}
                        </a>
                    </p>
                    @endif

                    @if($assignment->due_at)
                        <p class="font-semibold text-sm text-gray-600">
                            Due: {{ \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur')->format('d M Y H:i') }}
                        </p>
                    @endif

                    @if($role === 'teacher')
                        <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="btn-secondary inline-block px-4 py-2 text-sm">Edit</a>
                        <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger px-4 py-2 text-sm"
                                onclick="return confirm('Are you sure you want to delete this assignment?');">
                                Delete
                            </button>
                        </form>
                    @endif

                    @if($role === 'student')
                        @php
                            $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
                            $dueAt = $assignment->due_at ? \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur') : null;
                            $submittedAt = $submission ? \Carbon\Carbon::parse($submission->submitted_at)->timezone('Asia/Kuala_Lumpur') : null;
                        @endphp

                        <table class="mx-auto border border-gray-300 border-collapse">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Submitted At</th>
                                    <th class="px-4 py-2 text-left font-semibold w-48">Status</th> <!-- wider column -->
                                    <th class="px-4 py-2 text-left font-semibold">Previous Submission</th>
                                    <th class="px-4 py-2 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2">
                                        {{ $submission ? $submittedAt->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 w-48">
                                        @if($submission)
                                            @if($dueAt && $submittedAt->gt($dueAt))
                                                <span class="font-semibold text-red-600">Turned in Late</span>
                                            @else
                                                <span class="font-semibold text-green-600">Turned in On Time</span>
                                            @endif
                                        @else
                                            <span class="font-semibold text-gray-600">Not submitted yet</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 space-y-1">
                                        @if($submission)
                                            <a href="{{ asset('storage/' . $submission->file) }}" class="text-blue-600 underline block">
                                                {{ basename($submission->file) }}
                                            </a>

                                            {{-- Delete button now under previous submission --}}
                                            <form action="{{ route('student.assignments.deleteSubmission', $assignment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger px-3 py-1 text-sm mt-1"
                                                    onclick="return confirm('Are you sure you want to delete this submission?');">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        {{-- Submission Form --}}
                                        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="file" class="border rounded p-2 w-full mb-1" required>
                                            <button type="submit" class="btn-primary px-2 py-1 text-sm">Submit</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif


                </div> {{-- End app-card --}}
            @endforeach
        @else
            <p class="empty-message">No assignments uploaded yet.</p>
        @endif
    </div> {{-- End classbox --}}
</div> {{-- End class-container --}}
@endsection
