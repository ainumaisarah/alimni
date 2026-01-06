<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\User; // For teachers
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
{
        // Load schedules with classrooms and teachers
        $schedules = Schedule::with(['classroom', 'teacher'])->get();

        // Get classroom IDs that already have schedules
        $scheduledClassroomIds = $schedules->pluck('classroom_id');

        // Get classrooms without any schedule
        $unscheduledClassrooms = Classroom::whereNotIn('id', $scheduledClassroomIds)->get();

        return view('admin.schedules.index', compact(
            'schedules',
            'unscheduledClassrooms'
        ));
    }

    public function create(Request $request)
    {
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();

        // 👇 get classroom_id from query string
    $selectedClassroomId = $request->query('classroom_id');

        return view('admin.schedules.create', compact('classrooms', 'teachers','selectedClassroomId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule created successfully.');
    }

public function storeMultiple(Request $request)
{
    $classroom_id = $request->input('classroom_id');
    $teacher_id = $request->input('teacher_id');
    $schedules = $request->input('schedules', []);

    foreach ($schedules as $index => $schedule) {
        $schedule['classroom_id'] = $classroom_id;
        $schedule['teacher_id'] = $teacher_id;

        validator($schedule, [
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ])->validate();

        // Create schedule
        Schedule::create($schedule);
    }

    return redirect()->route('admin.schedules.index')
                     ->with('success', 'Schedules created successfully.');
}




    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule deleted successfully.');
    }

    public function home()
    {
        $schedules = Schedule::with(['classroom', 'teacher'])->get();
        return view('admin.home', compact('schedules'));
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.schedules.edit', compact('schedule', 'classrooms', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Schedule updated successfully.');
    }
}
