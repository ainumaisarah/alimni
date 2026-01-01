@extends('layouts.app')

@section('content')
<div class="page-container">

    <!-- Header -->
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.classrooms.index') }}"
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
        <h2>{{ $classroom->name }}</h2>
    </div>

    <p><strong>Teacher:</strong> {{ $classroom->teacher->name ?? 'N/A' }}</p>

    <div class="mt-4 app-card">

        <!-- Enrolled Students -->
        <h3>Students:</h3>

        @if($classroom->students->count())
            <ul class="mt-2 space-y-2">
                @foreach($classroom->students as $student)
                    <li class="flex items-center justify-between border-b pb-2">

                        <span>
                            {{ $student->name }}
                            <span class="text-sm text-gray-500">
                                ({{ $student->username }})
                            </span>
                        </span>

                        <form action="{{ route('admin.classroom.unenroll', [$classroom->id, $student->id]) }}"
                            method="POST"
                            onsubmit="return confirm('Unenroll this student?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-danger text-sm">
                                Unenroll
                            </button>
                        </form>

                    </li>
                @endforeach
            </ul>
        @else
            <p>No students enrolled in this class.</p>
        @endif

        <!-- Enroll Students -->
        <form action="{{ route('admin.classroom.enroll.submit', $classroom->id) }}"
              method="POST"
              class="mt-6">
            @csrf

            <h3 class="mb-2 font-semibold">Enroll Students</h3>
            <p class="text-sm text-gray-600 mb-3">
                Tick the checkbox next to the student you want to enroll.
            </p>

            <div class="border rounded p-4 max-h-64 overflow-y-auto">
                @forelse(
                    \App\Models\User::where('role', 'student')
                    ->whereDoesntHave('classrooms', function($q) use ($classroom) {
                        $q->where('classrooms.id', $classroom->id);
                    })
                    ->get() as $student
                )
                    <label class="flex items-center mb-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="students[]"
                            value="{{ $student->id }}"
                            class="mr-3"
                        >
                        <span>
                            {{ $student->name }}
                            <span class="text-sm text-gray-500">
                                ({{ $student->username }})
                            </span>
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500">
                        All students are already enrolled.
                    </p>
                @endforelse
            </div>

            <button type="submit" class="btn-primary mt-4">
                Enroll Selected Students
            </button>
        </form>

    </div>
</div>
@endsection
