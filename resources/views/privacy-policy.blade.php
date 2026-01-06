@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2 class="text-3xl font-bold mb-4">Privacy Policy</h2>
    <p class="mb-4">
        Your personal data (name, class, assignments, chat messages) is collected and used solely for educational purposes.
        Only teachers and administrators have access. Data is securely stored and encrypted.
    </p>
    <p class="mb-4">
        You may request to view or delete your data by contacting the school administrator.
    </p>

    <h2 class="text-xl font-bold mt-4">Data Collected</h2>
    <ul class="list-disc ml-6 mb-4">
        <li>Student name and class</li>
        <li>Assignments and uploaded documents</li>
        <li>Private chat messages with teachers</li>
        <li>Activity logs (login timestamps, recently accessed classes)</li>
    </ul>

    <h2 class="text-xl font-bold mt-4">Purpose of Collection</h2>
    <p class="mb-4">
        Data is used for educational management, tracking progress, teacher feedback, and safe communication.
    </p>

    <h2 class="text-xl font-bold mt-4">Retention & Access</h2>
    <p class="mb-4">
        Data is stored securely on Malaysian servers with daily backups. Admins and teachers can access data as required.
    </p>

    <h2 class="text-xl font-bold mt-4">User Rights</h2>
    <p class="mb-4">
        Students (and parents/guardians) may request to view, correct, or delete personal data by contacting the school administrator.
    </p>

    <h2 class="text-xl font-bold mt-4">Child Safety</h2>
    <p class="mb-4">
        Students under 18 should use this platform under parental supervision. All content is age-appropriate, and private chat is encrypted.
    </p>

</div>
@endsection
