@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>Admin Dashboard</h2>
    @if(session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-alert">
                {{ session('error') }}
            </div>
        @endif

    <div class="admin-card">
        <h3 class="text-lg font-semibold">Teachers</h3>
        <p class="text-lg font-bold">{{ $teacherCount }}</p>
        <a href="{{ route('admin.teachers.index') }}" class="btn-secondary">Manage</a><br>
        <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('admin.teachers.import') }}" class="btn-primary mt-2 inline-block">
            Import Teachers via Excel
        </a>
    </div>

    <div class="admin-card">
        <h3 class="text-lg font-semibold">Students</h3>
        <p class="text-lg font-bold">{{ $studentCount }}</p>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Manage</a><br>
        <a style="font-size: 14px; font-weight: 500; color: #f9fafa;" href="{{ route('admin.students.import') }}" class="btn-primary mt-2 inline-block">
            Import Students via Excel
        </a><br>
    </div>

    <!-- Consent & Audit Reports -->
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.consent.report') }}" class="btn-primary mt-4 inline-block">
            Student Consent Report
        </a><br>

        <a href="{{ route('admin.audit-report') }}" class="btn-primary mt-2 inline-block">
            Audit Report
        </a><br>

        <!-- Run Backup Button -->
        <div class="mt-4">
            <form id="backupForm" action="{{ route('admin.backups.run') }}" method="POST">
                @csrf
                <button type="submit" id="backupButton" class="btn-primary flex items-center gap-2">
                    <span id="backupText">Run Backup Now</span>
                    <svg id="loadingSpinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>
            </form>
        </div>
    @endif

</div>

<script>
    const backupForm = document.getElementById('backupForm');
    const backupButton = document.getElementById('backupButton');
    const backupText = document.getElementById('backupText');
    const spinner = document.getElementById('loadingSpinner');

    backupForm.addEventListener('submit', function() {
        backupButton.disabled = true;
        backupText.textContent = 'Running...';
        spinner.classList.remove('hidden');
    });
</script>
@endsection
