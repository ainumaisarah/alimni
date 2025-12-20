@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Admin Dashboard</h2>
        <div class="admin-card">
            <h3 class="text-lg font-semibold">Teachers</h3>
            <p class = "text-lg font-bold">{{ $teacherCount }}</p>
            <!--<a href="#" class="btn-secondary">Manage</a>-->
        </div>
        <div class="admin-card">
            <h3 class="text-lg font-semibold">Students</h3>
            <p class = "text-lg font-bold">{{ $studentCount }}</p>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Manage</a><br>
            <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('admin.students.import') }}" class="btn-primary mt-2 inline-block">
                Import Students via Excel
            </a>
        </div>
</div>
@endsection
