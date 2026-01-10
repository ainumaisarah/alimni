<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\User; // For teachers
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
   public function index(Request $request)
    {
        // 1️⃣ Get selected teacher id from dropdown
        $teacherId = $request->query('teacher_id');

        // 2️⃣ Get all teachers (for dropdown only)
        $teachers = User::where('role', 'teacher')->get();

        // 3️⃣ Get ONE selected teacher (Teacher POV)
        $selectedTeacher = null;

        if ($teacherId) {
            $selectedTeacher = User::with(['schedules.classroom'])
                ->where('id', $teacherId)
                ->first();
        }

        // 4️⃣ Unscheduled classrooms (global)
        $scheduledClassroomIds = Schedule::pluck('classroom_id')->unique();

        $unscheduledClassrooms = Classroom::whereNotIn('id', $scheduledClassroomIds)->get();

        return view('admin.schedules.index', compact(
            'teachers',
            'selectedTeacher',
            'unscheduledClassrooms',
            'teacherId'
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

    if (empty($schedules)) {
        return redirect()->back()->withErrors('No schedules provided.');
    }

    foreach ($schedules as $index => $schedule) {

        // Skip empty rows
        if (empty($schedule['day']) || empty($schedule['start_time']) || empty($schedule['end_time'])) {
            continue;
        }

        $schedule['classroom_id'] = $classroom_id;
        $schedule['teacher_id'] = $teacher_id;

        // -------------------------------
        // Basic validation
        // -------------------------------
        validator($schedule, [
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ])->validate();

        // -------------------------------
        // 🔴 CLASH CHECK (IMPORTANT FIX)
        // -------------------------------
        $clash = Schedule::where('teacher_id', $teacher_id)
            ->where('day', $schedule['day'])
            ->where(function ($q) use ($schedule) {
                $q->whereBetween('start_time', [$schedule['start_time'], $schedule['end_time']])
                  ->orWhereBetween('end_time', [$schedule['start_time'], $schedule['end_time']])
                  ->orWhere(function ($q2) use ($schedule) {
                      $q2->where('start_time', '<=', $schedule['start_time'])
                         ->where('end_time', '>=', $schedule['end_time']);
                  });
            })
            ->exists();

        if ($clash) {
            return redirect()->back()
                ->withErrors(
                    "Schedule clash detected: This teacher is already assigned on {$schedule['day']} from {$schedule['start_time']} to {$schedule['end_time']}."
                )
                ->withInput();
        }

        // -------------------------------
        // Save if no clash
        // -------------------------------
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
    $classroom_id = $request->input('classroom_id');
    $teacher_id = $request->input('teacher_id');
    $schedules = $request->input('schedules', []);

    if (empty($schedules)) {
        return redirect()->back()->withErrors('No schedules provided.');
    }

    // Optionally: delete old schedules for this classroom/teacher before re-adding
    Schedule::where('id', $id)->delete(); // or delete all schedules for this classroom if you want multiple

    foreach ($schedules as $schedule) {
        if (empty($schedule['day']) || empty($schedule['start_time']) || empty($schedule['end_time'])) {
            continue;
        }

        $schedule['classroom_id'] = $classroom_id;
        $schedule['teacher_id'] = $teacher_id;

        validator($schedule, [
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ])->validate();

        Schedule::create($schedule);
    }

    return redirect()->route('admin.schedules.index')
                     ->with('success', 'Schedule updated successfully.');
}

}
