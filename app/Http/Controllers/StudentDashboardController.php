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
    $classroomIds = $student->classrooms()->pluck('classrooms.id');

    // Get schedules for these classrooms
    $schedules = Schedule::with(['teacher', 'classroom'])
                        ->whereIn('classroom_id', $classroomIds)
                        ->get();

    // Retrieve recently accessed classrooms from session
    $recentClassroomsIds = session()->get('recent_classrooms', []);

    // If empty, fallback to first 3 enrolled classrooms
    if (empty($recentClassroomsIds)) {
        $recentClassroomsIds = $classroomIds->take(3)->toArray();
    }

    $recentClassrooms = Classroom::whereIn('id', $recentClassroomsIds)
                                 ->orderByRaw("FIELD(id," . implode(',', $recentClassroomsIds) . ")")
                                 ->get();

    return view('student.dashboard', compact('schedules', 'recentClassrooms'));
}


}


