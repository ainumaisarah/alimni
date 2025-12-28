<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        // Get classroom IDs the teacher is assigned to
        // 1️⃣ Directly assigned as teacher
        $directClassIds = $teacher->teachingClasses()->pluck('classrooms.id');

        // 2️⃣ Assigned via schedules
        $scheduledClassIds = Schedule::where('teacher_id', $teacher->id)
                                     ->pluck('classroom_id');

        // Merge and remove duplicates
        $classroomIds = $directClassIds->merge($scheduledClassIds)->unique();

        // Get schedules for these classrooms
        $schedules = Schedule::with(['teacher', 'classroom'])
                             ->whereIn('classroom_id', $classroomIds)
                             ->get();

        // ------------------------------
        // Recently accessed classrooms from session
        // ------------------------------
        $recentClassroomsIds = session()->get('recent_classrooms', []);

        // Fallback: show first 3 classes they teach if none in session
        if (empty($recentClassroomsIds)) {
            $recentClassroomsIds = $classroomIds->take(3)->toArray();
        }

        $recentClassroomsQuery = Classroom::whereIn('id', $recentClassroomsIds);

        if (!empty($recentClassroomsIds)) {
            $recentClassroomsQuery->orderByRaw(
                "FIELD(id," . implode(',', $recentClassroomsIds) . ")"
            );
        }

        $recentClassrooms = $recentClassroomsQuery->get();

        return view('teacher.dashboard', compact('schedules', 'recentClassrooms'));
    }
}
