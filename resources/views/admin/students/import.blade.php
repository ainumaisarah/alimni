@extends('layouts.app')

@section('content')
<div class="page-container mt-4">
    <h2 class="mb-4">Import Students</h2>

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
