<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Http\Controllers\Admin\StudentImportController;


class AdminDashboardController extends Controller
{
    public function index()
    {
        $classroomCount = Classroom::count();
        $teacherCount = User::where('role', 'teacher')->count();
        $studentCount = User::where('role', 'student')->count();

        return view('admin.dashboard', compact('classroomCount', 'teacherCount', 'studentCount'));
    }
}

