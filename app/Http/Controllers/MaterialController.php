<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // Teacher: list own uploaded materials
    public function teacherIndex()
    {
        $materials = Material::where('teacher_id', Auth::id())->with('classroom')->get();
        return view('teacher.materials.index', compact('materials'));
    }

    // Student: list materials for their classroom
    public function studentIndex()
    {
        $user = Auth::user();
        $materials = collect();
        if ($user->classroom_id) {
            $materials = Material::where('classroom_id', $user->classroom_id)->with('teacher')->get();
        }
        return view('student.materials.index', compact('materials'));
    }

    // Show create form (teacher)
    public function create(Request $request)
    {
    $classroomId = $request->input('classroom_id');
    $subjectId = $request->input('subject_id');

    $classrooms = Classroom::all(); // optional: filter to teacher's classes
    $subjects = Subject::where('teacher_id', auth()->id())->get();

    return view('teacher.materials.create', compact('classrooms', 'subjects', 'classroomId', 'subjectId'));
    }



    // Store uploaded file (teacher)
    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'file' => 'required|file',
        'classroom_id' => 'required|exists:classrooms,id',
        'subject_id' => 'required|exists:subjects,id',
    ]);

    $filePath = $request->file('file')->store('materials', 'public');

    Material::create([
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $filePath,
        'classroom_id' => $request->classroom_id,
        'subject_id' => $request->subject_id,
        'teacher_id' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Material uploaded successfully!');
    }


    // Download (teacher or student)
    public function download($id)
    {
        $material = Material::findOrFail($id);
        if (! $material->file_path) {
            abort(404);
        }
        return Storage::disk('public')->download($material->file_path, $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION));
    }

    // Optionally teacher can delete their material
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        if ($material->teacher_id != Auth::id()) {
            abort(403);
        }
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return redirect()->route('teacher.materials.index')->with('success', 'Material deleted.');
    }
}
