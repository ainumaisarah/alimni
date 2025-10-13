<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Classroom;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Assuming each student belongs to one classroom via classroom_id
        if (!$student->classroom_id) {
            return view('student.dashboard', ['schedules' => collect()]);
        }

        $schedules = Schedule::where('classroom_id', $student->classroom_id)
            ->with('teacher')
            ->get();

        return view('student.dashboard', compact('schedules'));
    }
}

