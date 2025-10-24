@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">My Materials</h2>

    <a href="{{ route('teacher.materials.create') }}" class="bg-blue-600 text-black px-4 py-2 rounded mb-4 inline-block">+ Upload Material</a>

    @if(session('success'))<div class="mb-3 text-green-600">{{ session('success') }}</div>@endif

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Classroom</th>
                <th class="px-4 py-2">Uploaded</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $m)
            <tr>
                <td class="border px-4 py-2">{{ $m->title }}</td>
                <td class="border px-4 py-2">{{ $m->classroom->name ?? 'N/A' }}</td>
                <td class="border px-4 py-2">{{ $m->created_at->format('Y-m-d') }}</td>
                <td class="border px-4 py-2 flex gap-2">
                    @if($m->file_path)
                        <a href="{{ route('teacher.materials.download', $m->id) }}" class="text-blue-600 hover:underline">Download</a>
                    @endif
                    <form action="{{ route('teacher.materials.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Delete this?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4">No materials yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
