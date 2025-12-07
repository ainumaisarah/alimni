@extends('layouts.app')

@section('content')
<div class="page-container">
    <h1 class="text-2xl font-bold mb-6">Manage Students</h1>

    @if(session('success'))
        <div class="success-alert mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Classroom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>
                    @if($student->classrooms->count())
                        {{ $student->classrooms->pluck('name')->join(', ') }}
                    @else
                        Not enrolled
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $student->id) }}" class="btn-secondary mr-3">Edit</a>

                    <form action="{{ route('admin.users.destroy', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
