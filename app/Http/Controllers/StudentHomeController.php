<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class StudentHomeController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Get announcements for all or for the student's classroom
        $announcements = Announcement::where(function($query) use ($student) {
            $query->whereNull('classroom_id')
                  ->orWhere('classroom_id', $student->classroom_id);
        })->latest()->take(10)->get();

        return view('student.home', compact('announcements'));
    }
}
