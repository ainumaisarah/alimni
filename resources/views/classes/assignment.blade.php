@extends('classes.show')

@section('content')
<div class="class-container">

    {{-- ================= NAV ================= --}}
    <nav class="class-nav">
        <div class="flex items-center gap-2">
            <a href="{{ route('classes.index') }}"
               class="h-8 w-8 inline-flex items-center justify-center p-2"
               style="color: rgb(224, 216, 191);">
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

            <h2>
                <a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
                    {{ $class->name }}
                </a>
            </h2>
        </div>

        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}"
               class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}">
                Materials
            </a>
            <a href="{{ route('classes.assignment', $class->id) }}"
               class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}">
                Assignment
            </a>
            <a href="{{ route('classes.quizzes', $class->id) }}"
               class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}">
                Quiz
            </a>
        </div>
    </nav>

    {{-- ================= FLASH ================= --}}
    @if(session('success'))
        <div class="success-alert">{{ session('success') }}</div>
    @endif

    {{-- ================= CONTENT ================= --}}
    <div class="classbox">
        <h3 style="font-size:22px;font-weight:650;color:#2b5948;">
            Assignments
        </h3>

        {{-- Teacher button --}}
        @if(auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.assignments.create', ['classroom_id' => $class->id]) }}"
               class="btn-primary mb-3 inline-block">
                Create New Assignment
            </a>
        @endif

        {{-- ================= LIST ================= --}}
        @forelse($assignments as $assignment)

            @php
                $dueAt = $assignment->due_at
                    ? \Carbon\Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur')
                    : null;

                $now = now('Asia/Kuala_Lumpur');
                $isLate = $dueAt && $now->gt($dueAt);
                $hoursLeft = $dueAt ? $now->diffInHours($dueAt, false) : null;
            @endphp

            <div class="app-card mb-4">

                {{-- TITLE --}}
                @if($role === 'teacher')
                    <h3 class="font-semibold text-lg">
                        <a href="{{ route('teacher.assignments.submissions', $assignment->id) }}"
                           class="text-blue-600 hover:underline">
                            {{ $assignment->title }}
                        </a>
                    </h3>
                @else
                    <h3 class="font-semibold text-lg">{{ $assignment->title }}</h3>
                @endif

                <p class="text-sm text-gray-800 font-medium">
                    {{ $assignment->description }}
                </p>

                {{-- FILE --}}
                @if($assignment->file)
                    <p class="text-sm">
                        <a href="{{ auth()->user()->role === 'teacher'
                            ? route('teacher.assignments.download', $assignment->id)
                            : route('student.assignments.download', $assignment->id) }}"
                           class="text-blue-600 underline">
                            {{ basename($assignment->file) }}
                        </a>
                    </p>
                @endif

                {{-- DUE --}}
                @if($dueAt)
                    <p class="text-sm text-gray-600">
                        Due: {{ $dueAt->format('d M Y H:i') }}
                    </p>
                @endif

                {{-- ================= STUDENT WARNINGS ================= --}}
                @if($role === 'student' && $dueAt && $hoursLeft > 0)

                    @if($hoursLeft <= 24 && $hoursLeft > 12)
                        <div class="warning-alert yellow mb-3">
                            ⚠️ Assignment is due in <strong>{{ $hoursLeft }} hours</strong>.
                        </div>

                    @elseif($hoursLeft <= 12 && $hoursLeft > 1)
                        <div class="warning-alert orange mb-3">
                            ⚠️ Urgent! Assignment is due in <strong>{{ $hoursLeft }} hours</strong>.
                        </div>

                    @elseif($hoursLeft <= 1)
                        <div class="warning-alert red flash mb-3">
                            🔔 <strong>DUE IN 1 HOUR!</strong> Submit immediately.
                        </div>
                    @endif

                @endif

                {{-- ================= TEACHER ACTIONS ================= --}}
                @if($role === 'teacher')
                    <a href="{{ route('teacher.assignments.edit', $assignment->id) }}"
                       class="btn-secondary px-4 py-2 text-sm">
                        Edit
                    </a>

                    <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}"
                          method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn-danger px-4 py-2 text-sm"
                                onclick="return confirm('Delete this assignment?')">
                            Delete
                        </button>
                    </form>
                @endif

                {{-- ================= STUDENT TABLE ================= --}}
                @if($role === 'student')

                    @php
                        $submission = $assignment->submissions()
                            ->where('student_id', auth()->id())
                            ->first();

                        $submittedAt = $submission
                            ? \Carbon\Carbon::parse($submission->submitted_at)->timezone('Asia/Kuala_Lumpur')
                            : null;
                    @endphp

                    <table class="mx-auto border border-gray-300 border-collapse mt-4 w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Submitted At</th>
                                <th class="px-4 py-2 text-left w-48">Status</th>
                                <th class="px-4 py-2 text-left">Previous Submission</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Submitted --}}
                                <td class="px-4 py-2">
                                    {{ $submittedAt ? $submittedAt->format('d M Y H:i') : '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-2">
                                    @if($submission)
                                        @if($isLate)
                                            <span class="text-red-600 font-semibold">Turned in Late</span>
                                        @else
                                            <span class="text-green-600 font-semibold">Turned in On Time</span>
                                        @endif
                                    @else
                                        <span class="text-gray-600">Not submitted yet</span>
                                    @endif
                                </td>

                                {{-- Previous --}}
                                <td class="px-4 py-2">
                                    @if($submission)
                                        <a href="{{ asset('storage/' . $submission->file) }}"
                                           class="text-blue-600 underline block">
                                            {{ basename($submission->file) }}
                                        </a>

                                        @if(!$isLate)
                                            <form action="{{ route('student.assignments.deleteSubmission', $assignment->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn-danger px-3 py-1 text-sm mt-1">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <p class="text-sm text-gray-500">
                                                Deletion disabled after due date
                                            </p>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-2">
                                    @if($isLate && !$assignment->allow_late_submission)
                                        <p class="text-red-600 font-semibold">
                                            Submission is closed
                                        </p>
                                    @else
                                        <form action="{{ route('student.assignments.submit', $assignment->id) }}"
                                              method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="file"
                                                   name="file"
                                                   class="border rounded p-2 w-full mb-1"
                                                   required>
                                            <button type="submit"
                                                    class="btn-primary px-2 py-1 text-sm">
                                                Submit
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif

            </div>

        @empty
            <p class="empty-message">No assignments uploaded yet.</p>
        @endforelse
    </div>
</div>
@endsection
