@extends('layouts.app')

@section('content')
<div class="page-container p-6">
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
        <h2 class="text-xl font-bold mb-4">Audit Report</h2>
    </div>

    <table class="border-collapse border w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-2 py-1">#</th>
                <th class="border px-2 py-1">User</th>
                <th class="border px-2 py-1">Action</th>
                <th class="border px-2 py-1">Properties</th>
                <th class="border px-2 py-1">Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
            <tr>
                <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                <td class="border px-2 py-1">{{ $activity->causer?->name ?? 'System' }}</td>
                <td class="border px-2 py-1">{{ $activity->description }}</td>
                <td class="border px-2 py-1">
                    <pre>{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</pre>
                </td>
                <td class="border px-2 py-1">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border px-2 py-1 text-center">No activity logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
