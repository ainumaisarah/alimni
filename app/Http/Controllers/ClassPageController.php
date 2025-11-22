<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;


class ClassPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

            if ($role === 'teacher') {
        // Get all schedules assigned to this teacher
        $schedules = Schedule::with('classroom')
            ->where('teacher_id', $user->id)
            ->get();

        // Get unique classrooms from these schedules
        $classes = $schedules->pluck('classroom')->unique('id');


        // Pass a flag to view to know teacher can create
        $canCreate = true;
    } else {
        // Student
        $classes = Classroom::with(['schedules'])
            ->where('id', $user->classroom_id)
            ->get();

        $canCreate = false;
    }

    return view('classes.index', compact('classes', 'role', 'canCreate'));

    }

    public function showClass(Classroom $class)
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'teacher') {
        // Teacher only sees their own materials/quizzes
        $materials = $class->materials()->where('teacher_id', $user->id)->get();
        $quizzes = $class->quizzes()->where('teacher_id', $user->id)->get();

        foreach ($quizzes as $quiz) {
            $quiz->result = null;
        }

        } else {
            // Student sees ALL materials/quizzes for the class
            $materials = $class->materials()->get();
            $quizzes = $class->quizzes()->get();

             foreach ($quizzes as $quiz) {
                $quiz->result = $quiz->results()
                    ->where('student_id', $user->id)
                    ->first();
            }
        }


        return view('classes.show', compact('class', 'materials', 'quizzes', 'role'));
    }

}
