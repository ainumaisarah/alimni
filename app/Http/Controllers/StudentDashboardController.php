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

    // Get classroom IDs of the student
    $classroomIds = $student->classrooms()->pluck('classrooms.id')->toArray();

    // Get schedules for these classrooms
    $schedules = Schedule::with(['teacher', 'classroom'])
                        ->whereIn('classroom_id', $classroomIds)
                        ->get();

    // Retrieve recently accessed classrooms from session
    $recentClassroomsIds = session()->get('recent_classrooms', []);

    if (empty($recentClassroomsIds)) {
        $recentClassroomsIds = array_slice($classroomIds, 0, 3);
    }

    $recentClassrooms = collect();

    if (!empty($recentClassroomsIds)) {
        $recentClassrooms = Classroom::whereIn('id', $recentClassroomsIds)
            ->orderByRaw("FIELD(id," . implode(',', $recentClassroomsIds) . ")")
            ->get();
    }

    return view('student.dashboard', compact('schedules', 'recentClassrooms'));
}


}


