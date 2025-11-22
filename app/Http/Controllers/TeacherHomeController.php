<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Announcement;

class TeacherHomeController extends Controller
{
    public function index()
    {
        // Only classrooms assigned to this teacher
        $classrooms = Classroom::where('teacher_id', auth()->id())->get();

        $announcements = Announcement::where('teacher_id', auth()->id())->latest()->get();

        return view('teacher.home', compact('classrooms', 'announcements'));
    }
}
