@extends('layouts.app')

@section('content')
<div class="page-container mt-6 max-w-3xl mx-auto">

    {{-- Back button + header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                   bg-gray-100 hover:bg-gray-200 rounded-lg
                   text-[#2b5948] hover:text-[#1f4033]">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-2xl font-semibold">Import Students</h2>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="success-alert mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-alert mb-4">{{ session('error') }}</div>
    @endif

    {{-- Import form --}}
        <form action="{{ route('admin.students.import.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-bold mb-2 text-lg">
                    📁 Select Excel/CSV File
                </label>
                <input type="file" name="file" id="file"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       accept=".xlsx,.xls,.csv" required>
            </div>
                    <button type="submit" class="btn btn-primary">Upload & Import</button>
        </form>
<br>
    {{-- Excel format table --}}
    <div class="mb-6">
        <p class="mb-2 font-semibold">Excel file format should be:</p>
        <div class="overflow-x-auto border rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border">Name</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border">Username</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border">Password</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border">Class Name</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border">Teacher Username</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">John Doe</td>
                        <td class="px-4 py-2 border">john123</td>
                        <td class="px-4 py-2 border">secret</td>
                        <td class="px-4 py-2 border">11A Science</td>
                        <td class="px-4 py-2 border">ali67</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
