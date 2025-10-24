@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Class Materials</h2>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Teacher</th>
                <th class="px-4 py-2">Uploaded</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $m)
            <tr>
                <td class="border px-4 py-2">{{ $m->title }}</td>
                <td class="border px-4 py-2">{{ $m->teacher->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $m->created_at->format('Y-m-d') }}</td>
                <td class="border px-4 py-2">
                    @if($m->file_path)
                        <a href="{{ route('student.materials.download', $m->id) }}" class="text-blue-600 hover:underline">Download</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4">No materials available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
