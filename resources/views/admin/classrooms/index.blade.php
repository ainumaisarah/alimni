@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Manage Classrooms</h2>

    <a href="{{ route('admin.classrooms.create') }}" class="btn-primary mb-4">
        + Create Classroom
    </a>

    <table>
        <thead>
            <tr>
                <th>Classroom Name</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classrooms as $classroom)
                <tr>
                    <td>
                        <a href="{{ route('admin.classrooms.show', $classroom->id) }}" class="hover:underline">
                            {{ $classroom->name }}
                        </a>
                    </td>
                    <td>{{ $classroom->teacher->name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn-secondary">Edit</a>

                            <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" onsubmit="return confirm('Delete this classroom?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4">No classrooms found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

  <!--  <a href="{{ route('admin.schedules.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mt-4 inline-block">
        Manage Schedules
    </a>-->
</div>
@endsection
