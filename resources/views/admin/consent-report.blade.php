{{-- resources/views/admin/consent-report.blade.php --}}
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
        <h2 class="mb-6">Student Consent Report</h2>
    </div>

    <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Student Name</th>
                <th class="px-4 py-2 text-left">Consent Given</th>
                <th class="px-4 py-2 text-left">Consent Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($students as $student)
            <tr>
                <td class="px-4 py-2">{{ $student->name }}</td>
                <td class="px-4 py-2">
                    @if($student->consent_given_at)
                        <span class="text-green-600 font-semibold">Yes</span>
                    @else
                        <span class="text-red-600 font-semibold">No</span>
                    @endif
                </td>
                <td class="px-4 py-2">
                    {{ $student->consent_given_at ? $student->consent_given_at->format('d M Y H:i') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
