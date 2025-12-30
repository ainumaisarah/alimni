<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

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

    // Update student's classroom enrollment
    public function update(Request $request, $id)
    {
        $request->validate([
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
        ]);

        $student = User::findOrFail($id);

        // Sync multiple classrooms
        $student->classrooms()->sync($request->classrooms ?? []);

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'user_id' => $student->id,
                'role' => $student->role,
                'classrooms' => $request->classrooms ?? [],
            ])
            ->log('Updated student enrollment');

        return redirect()->route('admin.users.index')
                         ->with('success', 'Student enrollment updated successfully.');
    }

    // Show classroom enrollment form
    public function showClassroomEnrollForm()
    {
        $classrooms = Classroom::all();
        $students = User::where('role', 'student')->get();

        return view('admin.users.classroom-enroll', compact('classrooms', 'students'));
    }

    // Enroll multiple students to a classroom
    public function enrollStudentsToClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:users,id',
        ]);

        // Add students without removing existing ones
        $classroom->students()->syncWithoutDetaching($request->students);

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'classroom_id' => $classroom->id,
                'students' => $request->students,
            ])
            ->log('Enrolled students to classroom');

        return redirect()->route('admin.classrooms.show', $classroom->id)
                         ->with('success', 'Students enrolled successfully.');
    }

    // Delete a student
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'user_id' => $student->id,
                'role' => $student->role,
            ])
            ->log('Deleted student account');

        return redirect()->route('admin.users.index')
                         ->with('success', 'Student deleted successfully.');
    }

    // Unenroll a student from a specific classroom
    public function unenrollStudent(Classroom $classroom, User $student)
    {
        $classroom->students()->detach($student->id);

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'classroom_id' => $classroom->id,
                'student_id' => $student->id,
            ])
            ->log('Unenrolled student from classroom');

        return redirect()->back()->with('success', 'Student unenrolled successfully.');
    }
}
