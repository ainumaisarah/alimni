@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2 class="mb-4">Dashboard</h2>

        <h3>Welcome, {{ auth()->user()->name }}!</h3>
        <p class="info-meta">
            This is the default dashboard. Please use your role-specific menu to navigate.</p>
</div>
@endsection
