<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Show create form (teacher)
    public function create()
    {
        $teacherId = Auth::id();
        $classrooms = Classroom::all(); // Or filter by teacher
        $subjects = Subject::where('teacher_id', $teacherId)->get();

        return view('teacher.announcements.create', compact('classrooms', 'subjects'));
    }

    // Store announcement
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'classroom_id' => 'nullable|exists:classrooms,id',
        'subject_id' => 'nullable|exists:subjects,id',
    ]);

    Announcement::create([
        'teacher_id' => Auth::id(),
        'title' => $validated['title'],
        'message' => $validated['message'],
        'classroom_id' => $validated['classroom_id'] ?? null,
        'subject_id' => $validated['subject_id'] ?? null,
    ]);

    return redirect()->route('teacher.home')->with('success', 'Announcement posted!');
}


    // List all announcements for teacher
    public function index()
    {
        $announcements = Announcement::where('teacher_id', Auth::id())->latest()->get();
        return view('teacher.announcements.index', compact('announcements'));
    }

    // List all announcements for students
    public function studentIndex()
    {
        $student = Auth::user();
        $announcements = Announcement::where('classroom_id', $student->classroom_id)
                                     ->orWhereNull('classroom_id')
                                     ->latest()
                                     ->get();
        return view('student.announcements.index', compact('announcements'));
    }

    // Show edit form
    public function edit(Announcement $announcement)
    {
        $teacherId = auth()->id();
        $subjects = Subject::where('teacher_id', $teacherId)->get();
        $classrooms = Classroom::where('teacher_id', $teacherId)->get();

        return view('teacher.announcements.edit', compact('announcement', 'subjects', 'classrooms'));
    }

    // Update the announcement
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        $announcement->update($validated);

    return redirect()->route('teacher.home')->with('success', 'Announcement updated!');
    }

    // Delete
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('teacher.home')->with('success', 'Announcement deleted!');
    }

}
