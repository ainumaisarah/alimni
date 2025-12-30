@extends('layouts.app')

@section('content')
<div class="schedule-container">
            {{-- Back button + header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                   bg-gray-100 hover:bg-gray-200 rounded-lg
                   text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold mb-4">PDPA Consent Report</h2>
    </div>

    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th>Name</th>
                <th>Consent</th>
                <th>Parent Consent</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>

                    <td>
                        {{ optional($student->consent_given_at)->format('d/m/Y H:i') ?? '❌ Not Given' }}
                    </td>

                    <td>
                        @if($student->age < 18)
                            {{ $student->parent_consented ? '✅ Yes' : '❌ No' }}
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        {{ optional($student->last_login_at)->format('d/m/Y H:i') ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
