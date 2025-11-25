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
    // Teacher
            $directClasses = Classroom::where('teacher_id', $user->id)->get();

            // Classes via schedules
            $scheduledClasses = Schedule::with('classroom')
                ->where('teacher_id', $user->id)
                ->get()
                ->pluck('classroom')
                ->unique('id');

            // Merge both
            $classes = $directClasses->merge($scheduledClasses)->unique('id');

            $canCreate = true;
        } else {
            // Student: fetch all classrooms via pivot table
            $classes = $user->classrooms()->with('schedules')->get();
            $canCreate = false;
        }

    return view('classes.index', compact('classes', 'role', 'canCreate'));

    }

    public function showClass(Classroom $class)
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'teacher') {
            $materials = $class->materials()->where('teacher_id', $user->id)->get();
            $quizzes = $class->quizzes()->where('teacher_id', $user->id)->get();
        } else {
            $materials = $class->materials()->get();
            $quizzes = $class->quizzes()->get();
        }

        return view('classes.show', compact('class', 'materials', 'quizzes', 'role'));
    }



}
