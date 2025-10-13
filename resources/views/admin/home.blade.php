@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Admin Home
        </h2>
        <p>Welcome, {{ auth()->user()->name }}!</p>
    </div>

    <a href="{{ route('admin.schedules.index') }}" class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700 mt-4 inline-block">
    Manage Schedules
    </a>
@endsection
