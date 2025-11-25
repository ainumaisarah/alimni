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

        // Get the IDs of classrooms this student belongs to
        $classroomIds = $student->classrooms()->pluck('classroom_id');

        // Get schedules for these classrooms
        $schedules = Schedule::with(['teacher', 'classroom'])
                            ->whereIn('classroom_id', $classroomIds)
                            ->get();

        return view('student.dashboard', compact('schedules'));
    }

}

