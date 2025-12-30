@extends('layouts.app')

@section('content')
<div class="schedule-container">
    <h1 class="text-2xl font-bold mb-4">PDPA Consent Report</h1>

    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th>Name</th>
                <th>Consent</th>
                <th>Parent Consent</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>

                    <td>
                        {{ optional($student->consent_given_at)->format('d/m/Y H:i') ?? '❌ Not Given' }}
                    </td>

                    <td>
                        @if($student->age < 18)
                            {{ $student->parent_consented ? '✅ Yes' : '❌ No' }}
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        {{ optional($student->last_login_at)->format('d/m/Y H:i') ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
