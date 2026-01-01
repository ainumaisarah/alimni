<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ConsentReportController extends Controller
{
    public function index()
    {
        // Fetch all students
        $students = User::where('role', 'student')
                        ->orderBy('name')
                        ->get();

        return view('admin.consent-report', compact('students'));
    }
}
