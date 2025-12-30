<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Add this
use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\ActivitiesExport;

class AuditReportController extends Controller
{
    public function index(Request $request) // <-- Inject Request
    {
        $activities = Activity::query();

        // Filter by user name
        if($request->user){
            $activities->whereHas('causer', fn($q) => $q->where('name', 'like', "%{$request->user}%"));
        }

        // Filter by action description
        if($request->action){
            $activities->where('description', 'like', "%{$request->action}%");
        }

        $activities = $activities->latest()->get();

        return view('admin.audit-report', compact('activities'));
    }

    public function exportAuditReport()
    {
        $activities = \Spatie\Activitylog\Models\Activity::all();

        return Excel::download(new ActivitiesExport($activities), 'audit-report.xlsx');
    }

    public function export()
    {
        $activities = \Spatie\Activitylog\Models\Activity::all();
        return Excel::download(new ActivitiesExport($activities), 'audit-report.xlsx');
    }

}

