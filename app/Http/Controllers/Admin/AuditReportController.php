<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AuditReportController extends Controller
{
    public function index()
    {
        // Fetch all activity logs, latest first
        $activities = Activity::latest()->get();

        return view('admin.audit-report', compact('activities'));
    }
}
