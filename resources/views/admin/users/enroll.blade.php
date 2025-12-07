@extends('layouts.app')

@section('content')
<div class="psge-container">
    <h2>Enroll {{ $user->name }} in a Classroom</h2>

    @if ($errors->any())
        <div class="error-alert mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.enroll.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="app-card">
            <label for="classroom_id" class="block font-medium mb-1">Select Classroom</label>
            <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2" required>
                <option value="" disabled selected>Select a classroom</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $user->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">Enroll Student</button>
    </form>
</div>
@endsection
