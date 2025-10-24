@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Subjects</h1>

    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary mb-3">Add New Subject</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Assigned Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->teacher->name ?? 'Unassigned' }}</td>
                            <td>
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this subject?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No subjects available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
