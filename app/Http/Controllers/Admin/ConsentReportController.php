<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsentReportController extends Controller
{
    public function index()
    {
        // Fetch only PDPA-related and student activity logs
        $activities = Activity::whereIn('description', [
            'Student gave PDPA consent',
            'Uploaded assignment',
        ])->latest()->get();

        return view('admin.consent-report', compact('activities'));
    }
}
