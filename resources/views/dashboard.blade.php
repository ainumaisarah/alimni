@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <p>This is the default dashboard. Please use your role-specific menu to navigate.</p>
</div>
@endsection
