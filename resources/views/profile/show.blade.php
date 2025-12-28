@extends('layouts.app')

@section('content')
<div class="page-container mt-6 max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6 text-left-align">
        <h2 class="text-3xl font-bold text-gray-800">Profile</h2>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="success-alert mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-alert mb-4">{{ session('error') }}</div>
    @endif

    {{-- Update Name Form --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white transition hover:shadow-lg">
        <h3 class="text-3xl font-semibold mb-4 text-gray-700">Update Name</h3>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Enter your name" required>
            </div>
            <button type="submit" class="btn btn-primary w-full">Update Name</button>
        </form>
    </div>

    {{-- Update Password Form --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white transition hover:shadow-lg">
        <h3 class="text-3xl font-semibold mb-4 text-gray-700">Update Password</h3>
        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="current_password" class="block font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" id="current_password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Enter current password" required>
            </div>

            <div class="mb-4">
                <label for="new_password" class="block font-medium mb-1">New Password</label>
                <input type="password" name="new_password" id="new_password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Enter new password" required>
            </div>

            <div class="mb-4">
                <label for="new_password_confirmation" class="block font-medium mb-1">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="Confirm new password" required>
            </div>

            <button type="submit" class="btn btn-primary w-full">Update Password</button>
        </form>
    </div>

    {{-- Update Profile Photo --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white transition hover:shadow-lg">
        <h3 class="text-3xl font-semibold mb-4 text-gray-700">Update Profile Photo</h3>
        <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 mb-4">
                <input type="file" name="profile_photo" id="profile_photo"
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" required>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Upload</button>

                    @if(auth()->user()->profile_photo_path)
                        <form action="{{ route('profile.removePhoto') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    @endif
                </div>
            </div>

            @if(auth()->user()->profile_photo_path)
            <div class="mt-4">
                <p class="font-semibold mb-2">Current Photo:</p>
                <img
                    src="{{ asset(auth()->user()->profile_photo_path) }}"
                    alt="Profile Photo"
                    class="rounded-full object-cover"
                    style="width:120px; height:120px;"
                >
            </div>
            @endif
        </form>
    </div>

</div>
@endsection
