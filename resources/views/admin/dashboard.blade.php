@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Classrooms</h3>
            <p>{{ $classroomCount }}</p>
           <div>
                <a href="{{ route('admin.classrooms.index') }}" class="text-blue-500 underline">Manage</a><br>
                <a href="{{ route('admin.classrooms.overview') }}" class="btn btn-primary">Classroom Overview</a><br>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Teachers</h3>
            <p>{{ $teacherCount }}</p>
            <a href="#" class="text-blue-500 underline">Manage</a>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Students</h3>
            <p>{{ $studentCount }}</p>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Manage</a><br>
            <a href="{{ route('admin.students.import') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                Import Students via Excel
            </a>
        </div>

    </div>
</div>
@endsection
