@extends('layouts.app')

@section('content')
<div class="page-container mt-6 max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Profile</h2>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="success-alert mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-alert mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ================= Update Name ================= --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white">
        <h3 class="text-2xl font-semibold mb-4 text-gray-700">Update Name</h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ auth()->user()->name }}"
                    class="w-full border rounded-lg px-3 py-2"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Update Name
            </button>
        </form>
    </div>

    {{-- ================= Update Password ================= --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white">
        <h3 class="text-2xl font-semibold mb-4 text-gray-700">Update Password</h3>

        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">New Password</label>
                <input type="password" name="new_password" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Update Password
            </button>
        </form>
    </div>

    {{-- ================= Profile Photo ================= --}}
    <div class="mb-6 p-6 border rounded-xl shadow-md bg-white">
        <h3 class="text-2xl font-semibold mb-4 text-gray-700">Profile Photo</h3>

        {{-- Upload photo --}}
        <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3 mb-4">
                <input type="file" name="profile_photo" class="flex-1 border rounded-lg px-3 py-2" required>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>

        {{-- Remove photo --}}
        @if(auth()->user()->profile_photo_path)
            <form action="{{ route('profile.photo.delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger mb-4">
                    Remove Photo
                </button>
            </form>
        @endif

        {{-- Current photo --}}
        @if(auth()->user()->profile_photo_path)
            <div class="mt-4">
                <p class="font-semibold mb-2">Current Photo:</p>
                <img
                    src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                    alt="Profile Photo"
                    class="rounded-full object-cover"
                    style="width:120px; height:120px;"
                >
            </div>
        @endif
    </div>

</div>
@endsection
