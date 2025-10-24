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
    public function create()
    {
    $classrooms = Classroom::where('teacher_id', Auth::id())->get();
    return view('teacher.materials.create', compact('classrooms'));
    }


    // Store uploaded file (teacher)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'file' => 'nullable|file|max:10240', // 10MB
            'description' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public'); // stores in storage/app/public/materials
        }

        Material::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'classroom_id' => $request->classroom_id,
            'teacher_id' => Auth::id(),
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Material uploaded.');
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
