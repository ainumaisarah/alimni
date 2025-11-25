<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;

class UserController extends Controller
{
    // List all students
    public function index()
    {
        // Eager load the classrooms relationship
        $students = User::where('role', 'student')->with('classrooms')->get();

        $classroomCount = Classroom::count();

        return view('admin.users.index', compact('students', 'classroomCount'));
    }

    // Show the form to edit student's classroom
    public function edit($id)
    {
        $student = User::findOrFail($id);
        $classrooms = Classroom::all();

        return view('admin.users.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
        ]);

        $student = User::findOrFail($id);

        // Sync multiple classrooms
        $student->classrooms()->sync($request->classrooms ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Student enrollment updated successfully.');
    }


    public function showClassroomEnrollForm()
    {
        $classrooms = \App\Models\Classroom::all();
        $students = \App\Models\User::where('role', 'student')->get();

        return view('admin.users.classroom-enroll', compact('classrooms', 'students'));
    }

    public function enrollStudentsToClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:users,id',
        ]);

        // Sync selected students to classroom
        $classroom->students()->sync($request->students);

        return redirect()->route('admin.classrooms.show', $classroom->id)
                        ->with('success', 'Students enrolled successfully.');
    }


    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.users.index')->with('success', 'Student deleted successfully.');
    }


}
