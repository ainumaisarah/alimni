<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Announcement;

class TeacherHomeController extends Controller
{
    public function index()
{
   $classrooms = Classroom::all(); // or filter by teacher
    $subjects = Subject::where('teacher_id', auth()->id())->get();
    $announcements = Announcement::where('teacher_id', auth()->id())->latest()->get();
    return view('teacher.home', compact('classrooms', 'subjects', 'announcements'));
}
}
