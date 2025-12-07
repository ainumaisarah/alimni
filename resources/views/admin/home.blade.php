@extends('layouts.app')

@section('content')
    <div class="page-container">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Admin Home
        </h2>
        <p>Welcome, {{ auth()->user()->name }}!</p>
    </div>

    <a href="{{ route('admin.schedules.index') }}" class="btn-primary">
    Manage Schedules
    </a>
@endsection
