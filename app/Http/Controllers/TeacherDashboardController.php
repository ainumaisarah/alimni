<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('teacher_id', auth()->id())->with('classroom')->get();
        return view('teacher.dashboard', compact('schedules'));
    }
}

