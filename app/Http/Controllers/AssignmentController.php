<?php

namespace App\Http\Controllers;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    // Teacher: create form
    public function create($classroom_id)
    {
        return view('teacher.assignments.create', compact('classroom_id'));
    }

    // Teacher: store assignment
public function store(Request $request)
{
    $request->validate([
        'classroom_id' => 'required|exists:classrooms,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt',
    ]);

    $filePath = null;

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $filePath = $file->storeAs('assignments', $filename);
    }

    $assignment = Assignment::create([
        'classroom_id' => $request->classroom_id,
        'title' => $request->title,
        'description' => $request->description,
        'file' => $filePath,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('classes.assignment', $request->classroom_id)
    ->with('success', 'Assignment created successfully!');
}


    // Teacher: edit form
    public function edit(Assignment $assignment)
    {
        return view('teacher.assignments.edit', compact('assignment'));
    }

    // Teacher: update
    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($assignment->file) {
                Storage::disk('public')->delete($assignment->file);
            }
            $data['file'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update($data);

        return redirect()->back()->with('success', 'Assignment updated.');
    }

    // Teacher: delete
    public function destroy(Assignment $assignment)
    {
        if ($assignment->file) {
            Storage::disk('public')->delete($assignment->file);
        }
        $assignment->delete();
        return redirect()->back()->with('success', 'Assignment deleted.');
    }

    // Teacher & Student: download file
    public function download($assignmentId)
{
    $assignment = Assignment::findOrFail($assignmentId);

    if (!$assignment->file) {
        return back()->with('error', 'No file uploaded for this assignment.');
    }

    return response()->download(storage_path('app/' . $assignment->file));
}



    // Student: submit assignment
    public function submit(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file')->store('submissions', 'public');

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
            ],
            [
                'file' => $file
            ]
        );

        return redirect()->back()->with('success', 'Assignment submitted.');
    }
}
