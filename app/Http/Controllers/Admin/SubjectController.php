<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\User;

class SubjectController extends Controller
{
    // Show all subjects
    public function index()
    {
        $subjects = Subject::with('teacher')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    // Show create form
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    // Store a new subject
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        Subject::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    // Update a subject
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    // Delete a subject
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
