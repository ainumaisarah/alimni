@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="mb-4">My Materials</h2>

    <a href="{{ route('teacher.materials.create') }}"
       class="bg-blue-600 text-black px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
       + Upload Material
    </a>

    @if(session('success'))
        <div class="empty-message mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    @if($materials->isEmpty())
        <p class="empty-message">No materials yet.</p>
    @else
        <div class="info-card overflow-x-auto">
            <table class="w-full border-collapse text-center">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Classroom</th>
                        <th class="px-4 py-2 border">Uploaded</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $m->title }}</td>
                        <td class="border px-4 py-2">{{ $m->classroom->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $m->created_at->format('Y-m-d') }}</td>
                        <td class="border px-4 py-2 flex justify-center gap-2">
                            @if($m->file_path)
                                <a href="{{ route('teacher.materials.download', $m->id) }}"
                                   class="bg-blue-500 text-black px-3 py-1 rounded hover:bg-blue-600 transition">
                                   Download
                                </a>
                            @endif
                            <form action="{{ route('teacher.materials.destroy', $m->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this material?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 text-black px-3 py-1 rounded hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
