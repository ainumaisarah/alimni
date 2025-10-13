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
        $students = User::where('role', 'student')->with('classroom')->get();

        // Count all classrooms (or modify as needed)
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

    // Update student's classroom assignment
    public function update(Request $request, $id)
    {
        $request->validate([
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        $student = User::findOrFail($id);
        $student->classroom_id = $request->classroom_id;
        $student->save();

        return redirect()->route('admin.users.index')->with('success', 'Student enrollment updated successfully.');
    }

    public function showClassroomEnrollForm()
    {
        $classrooms = \App\Models\Classroom::all();
        $students = \App\Models\User::where('role', 'student')->get();

        return view('admin.users.classroom-enroll', compact('classrooms', 'students'));
    }

    public function enrollStudentsToClassroom(Request $request, $classroomId)
    {
        $classroom = \App\Models\Classroom::findOrFail($classroomId);

        // Update each selected student's classroom
        \App\Models\User::whereIn('id', $request->student_ids)
            ->update(['classroom_id' => $classroom->id]);

        return redirect()->route('admin.dashboard')->with('success', 'Students enrolled successfully!');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.users.index')->with('success', 'Student deleted successfully.');
    }


}
