@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">My Class-Subject Groups</h2>

    @if(count($groups) > 0)
        <ul class="list-group">
            @foreach($groups as $group)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $group['class']->name }}, {{ $group['subject']->name }}

                    @if($role === 'Teacher')
                        <a href="{{ route('teacher.materials.create', ['subject_id' => $group['subject']->id, 'classroom_id' => $group['class']->id]) }}"
                           class="btn btn-sm btn-success">Upload Material</a>
                    @else
                        <a href="{{ route('student.classes.subject.show', $group['subject']->id) }}"
                           class="btn btn-sm btn-primary">View</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>No classes or subjects assigned.</p>
    @endif
</div>
@endsection
