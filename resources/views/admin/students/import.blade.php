@extends('layouts.app')

@section('content')
<div class="page-container mt-4">
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.dashboard') }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                    bg-gray-100 hover:bg-gray-200 rounded-lg
                    text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="mb-4">Import Students</h2>
    </div>

    @if(session('success'))
        <div class="success-alert mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-alert mb-4">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.students.import.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Select Excel/CSV File</label>
            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload & Import</button>
    </form>

   <div class="mt-4">
    <p class="mb-2">Excel file format should be:</p>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border">name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border">username</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border">password</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border">class_name</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border">teacher_username</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-4 py-2 border">John Doe</td>
                    <td class="px-4 py-2 border">john123</td>
                    <td class="px-4 py-2 border">secret</td>
                    <td class="px-4 py-2 border">1A Science</td>
                    <td class="px-4 py-2 border">ainumai</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection
