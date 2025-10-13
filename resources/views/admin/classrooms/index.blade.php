@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Manage Classrooms</h2>

    <a href="{{ route('admin.classrooms.create') }}" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
        + Create Classroom
    </a>

    <table class="mt-4 w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Classroom Name</th>
                <th class="border border-gray-300 px-4 py-2">Teacher</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classrooms as $classroom)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $classroom->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $classroom->teacher->name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-sm">Edit</a>

                        <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" onsubmit="return confirm('Delete this classroom?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                        </form>
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
