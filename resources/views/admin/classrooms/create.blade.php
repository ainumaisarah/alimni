@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Create New Classroom</h2>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.store') }}" method="POST">
        @csrf

        <div class="app-card">
            <label for="name">Classroom Name</label>
            <input type="text" name="name" id="name" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}" required>



            <label for="teacher_id">Assign Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select Teacher --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>

        </div>

        <button type="submit" class="btn-primary">Create Classroom</button>
    </form>
</div>
@endsection
