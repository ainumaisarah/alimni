<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\User;

class ClassroomController extends Controller
{
   public function index()
    {
        $classrooms = Classroom::with('teacher')->get();
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.classrooms.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created.');
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.classrooms.edit', compact('classroom', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classroom = Classroom::findOrFail($id);
        $classroom->name = $request->input('name');
        $classroom->teacher_id = $request->input('teacher_id');
        $classroom->save();

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom updated successfully.');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom deleted successfully.');
    }

    public function overview()
    {
            $classrooms = \App\Models\Classroom::with(['students', 'schedules.teacher'])->get();

            return view('admin.classrooms.overview', compact('classrooms'));
        }

    public function show($id)
    {
        // Load classroom with teacher and students
        $classroom = Classroom::with(['teacher', 'students'])->findOrFail($id);

        return view('admin.classrooms.show', compact('classroom'));
    }

}
