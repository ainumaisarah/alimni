<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsentReportController extends Controller
{
   public function index()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.consent-report', compact('students'));
    }
}
