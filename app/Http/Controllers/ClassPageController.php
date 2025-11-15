<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Subject;

class ClassPageController extends Controller
{
    // List all classes for the user
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $classes = collect();

        if($role === 'Teacher') {
    $teacherId = $user->id;

    // get all class IDs where this teacher has subjects
    $classIds = Subject::where('teacher_id', $teacherId)->pluck('classroom_id')->unique();

    // fetch the classrooms
    $classes = Classroom::whereIn('id', $classIds)->get();
    }


        return view('classes.index', compact('classes', 'role'));
    }

    public function showSubject($subjectId)
{
    $user = Auth::user();
    $subject = Subject::with('teacher', 'materials', 'quizzes')->findOrFail($subjectId);

    if ($user->hasRole('student') && $user->classroom_id !== $subject->classroom_id) {
        return redirect()->back()->with('error', 'You cannot access this subject.');
    }

    return view('classes.subject', [
        'subject' => $subject,
        'role' => $user->role,
        'classroomId' => $subject->classroom_id
    ]);
}


}
