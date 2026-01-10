<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = $request->query('teacher_id');

        $teachers = User::where('role', 'teacher')->get();

        $selectedTeacher = $teacherId
            ? User::with('schedules.classroom')->find($teacherId)
            : null;

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
        $selectedClassroomId = $request->query('classroom_id');

        return view('admin.schedules.create', compact(
            'classrooms',
            'teachers',
            'selectedClassroomId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id'   => 'required|exists:users,id',
            'day'          => 'required|string|max:50',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
        ]);

        $clash = $this->checkClash(
            $validated['teacher_id'],
            $validated['day'],
            $validated['start_time'],
            $validated['end_time']
        );

        if ($clash) {
            return redirect()->back()
                ->withErrors($clash)
                ->withInput();
        }

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

            $clash = $this->checkClash(
                $teacher_id,
                $schedule['day'],
                $schedule['start_time'],
                $schedule['end_time']
            );

            if ($clash) {
                return redirect()->back()
                    ->withErrors($clash)
                    ->withInput();
            }

            Schedule::create($schedule);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedules created successfully.');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.schedules.edit', compact('schedule', 'classrooms', 'teachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id'   => 'required|exists:users,id',
            'schedules'    => 'required|array',
        ]);

        $item = $request->schedules[0]; // only single schedule edit

        $clash = $this->checkClash(
            $request->teacher_id,
            $item['day'],
            $item['start_time'],
            $item['end_time'],
            $schedule->id
        );

        if ($clash) {
            return redirect()->back()
                ->withErrors($clash)
                ->withInput();
        }

        $schedule->update([
            'classroom_id' => $request->classroom_id,
            'teacher_id'   => $request->teacher_id,
            'day'          => $item['day'],
            'start_time'   => $item['start_time'],
            'end_time'     => $item['end_time'],
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    protected function checkClash($teacher_id, $day, $start_time, $end_time, $excludeId = null)
    {
        $query = Schedule::where('teacher_id', $teacher_id)
            ->where('day', $day);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->where(function ($q) use ($start_time, $end_time) {
            $q->whereBetween('start_time', [$start_time, $end_time])
              ->orWhereBetween('end_time', [$start_time, $end_time])
              ->orWhere(function ($q2) use ($start_time, $end_time) {
                  $q2->where('start_time', '<=', $start_time)
                     ->where('end_time', '>=', $end_time);
              });
        })->exists();

        if ($exists) {
            return "Schedule clash detected: This teacher is already assigned on {$day} from {$start_time} to {$end_time}.";
        }

        return false;
    }
}
