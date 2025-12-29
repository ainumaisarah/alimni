<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Classroom;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
   public function index()
    {
        $student = auth()->user();

        $classroomIds = $student->classrooms()
                                ->pluck('classrooms.id')
                                ->toArray();

        $schedules = Schedule::with(['teacher', 'classroom'])
                             ->whereIn('classroom_id', $classroomIds)
                             ->get();

        // Time slots (30 min)
        $timeSlots = [];
        $start = Carbon::parse('08:00');
        $end = Carbon::parse('16:00');
        while ($start->lte($end)) {
            $timeSlots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        // Schedule matrix
        $scheduleMatrix = [];
        foreach ($schedules as $schedule) {
            $scheduleMatrix[$schedule->day][] = [
                'start' => Carbon::parse($schedule->start_time),
                'end' => Carbon::parse($schedule->end_time),
                'classroom' => $schedule->classroom->name ?? 'N/A',
                'teacher' => $schedule->teacher->name ?? 'N/A',
            ];
        }

        // Recently accessed classes
        $recentClassroomsIds = session()->get('recent_classrooms', []);
        if (empty($recentClassroomsIds)) {
            $recentClassroomsIds = array_slice($classroomIds, 0, 3);
        }

        $recentClassrooms = Classroom::whereIn('id', $recentClassroomsIds)->get();

        return view('student.dashboard', compact(
            'schedules',
            'timeSlots',
            'scheduleMatrix',
            'recentClassrooms'
        ));
    }
}


