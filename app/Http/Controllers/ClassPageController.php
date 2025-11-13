<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Subject;


class ClassPageController extends Controller
{
    public function index()
    {
    $user = Auth::user();

    // For Teachers
    if ($user->hasRole('teacher')) {
        $groups = [];
        $classes = Classroom::with(['subjects' => function($q) use ($user) {
            $q->where('teacher_id', $user->id)->with('teacher');
        }])->get();

        foreach ($classes as $class) {
            foreach ($class->subjects as $subject) {
                $groups[] = [
                    'class' => $class,
                    'subject' => $subject,
                ];
            }
        }

        return view('classes.index', compact('groups'))->with('role', 'Teacher');
    }

    // For Students
    if ($user->hasRole('student')) {
        $class = $user->classroom()->with('subjects.teacher')->first();

        $groups = [];
        foreach ($class->subjects as $subject) {
            $groups[] = [
                'class' => $class,
                'subject' => $subject,
            ];
        }

        return view('classes.index', compact('groups'))->with('role', 'Student');
    }

    return redirect()->route('dashboard')->with('error', 'Only teachers or students can view classes.');
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
