<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function consentReport()
    {
        // Fetch all students (or all users if needed)
        $students = User::where('role', 'student')->get();

        return view('admin.consent-report', compact('students'));
    }
}
