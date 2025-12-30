<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ConsentReportController extends Controller
{
    public function index()
    {
        // Fetch only PDPA-related and student activity logs
        $activities = Activity::whereIn('description', [
            'Student gave PDPA consent',
            'Parent consented',
        ])->latest()->get();

        return view('admin.consent-report', compact('activities'));
    }
}
