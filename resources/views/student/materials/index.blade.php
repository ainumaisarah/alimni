@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Class Materials</h2>

    <table>
        <thead>
            <tr>
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
            <tr><td colspan="4" class="empty-message">No materials available.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
