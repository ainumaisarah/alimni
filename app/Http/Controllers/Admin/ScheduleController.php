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
        $schedules = Schedule::with(['classroom', 'teacher'])->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.schedules.create', compact('classrooms', 'teachers'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }


    // In your Admin/ScheduleController.php (or wherever your schedules logic is)
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
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
            'subject' => 'required|string|max:255',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }


}
