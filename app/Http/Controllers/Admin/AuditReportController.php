<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivitiesExport;

class AuditReportController extends Controller
{
    // Show audit logs with optional filtering
    public function index(Request $request)
    {
        $activities = Activity::query();

        // Filter by user name
        if ($request->filled('user')) {
            $activities->whereHas('causer', fn($q) => $q->where('name', 'like', "%{$request->user}%"));
        }

        // Filter by action description
        if ($request->filled('action')) {
            $activities->where('description', 'like', "%{$request->action}%");
        }

        $activities = $activities->latest()->get();

        return view('admin.audit-report', compact('activities'));
    }

    public function exportCsv()
    {
        $activities = \Spatie\Activitylog\Models\Activity::latest()->get();

        $filename = 'audit-report-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['User', 'Action', 'Properties', 'Date & Time'];

        $callback = function() use ($activities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->causer?->name ?? 'System',
                    $activity->description,
                    json_encode($activity->properties->toArray()),
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    // Export all activities to Excel
    public function export()
    {
        $activities = Activity::all();
        return Excel::download(new ActivitiesExport($activities), 'audit-report.xlsx');
    }
}
