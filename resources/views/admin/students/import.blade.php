@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Import Students</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
        <p>Excel file format should be:</p>
        <table class="table table-bordered w-50">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>john123</td>
                    <td>secret</td>
                    <td>Class A</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
