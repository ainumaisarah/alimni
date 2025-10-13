@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Student Home
        </h2>
        <p>Welcome, {{ auth()->user()->name }}!</p>
    </div>
@endsection
