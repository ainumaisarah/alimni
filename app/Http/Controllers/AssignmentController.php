<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'due_at' => 'nullable|date',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // Store in /storage/app/public/assignments with original name
            $filePath = $file->storeAs('assignments', $filename, 'public');
        }

        // Convert due_at to UTC before saving
        $dueAtUtc = $request->due_at
            ? Carbon::parse($request->due_at, 'Asia/Kuala_Lumpur')->setTimezone('UTC')
            : null;

        $assignment = Assignment::create([
            'classroom_id' => $request->classroom_id,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $filePath,
            'user_id' => auth()->id(),
            'due_at' => $dueAtUtc,
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
    public function update(Request $request, $id)
{
    $assignment = Assignment::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'file' => 'nullable|file|max:20480', // 20MB
        'due_at' => 'nullable|date',
    ]);

    // If teacher uploads a new file
    if ($request->hasFile('file')) {
        // Delete old file if exists
        if ($assignment->file && Storage::disk('public')->exists($assignment->file)) {
            Storage::disk('public')->delete($assignment->file);
        }

        $originalName = $request->file('file')->getClientOriginalName();
        $storedPath = $request->file('file')->storeAs('assignments', $originalName, 'public');
        $assignment->file = $storedPath;
    }

    $assignment->title = $request->title;
    $assignment->description = $request->description;

    // Convert due_at to UTC before saving
    $assignment->due_at = $request->due_at
        ? \Carbon\Carbon::parse($request->due_at, 'Asia/Kuala_Lumpur')->setTimezone('UTC')
        : null;

    $assignment->save();

    return redirect()->route('classes.assignment', $assignment->classroom_id)
        ->with('success', 'Assignment updated successfully!');
}



    // Teacher: delete assignment
    public function destroy(Assignment $assignment)
    {
        if ($assignment->file) {
            Storage::disk('public')->delete($assignment->file);
        }

        $assignment->delete();

        return back()->with('success', 'Assignment deleted.');
    }


    // Teacher & Student: download assignment file
    public function download($id)
    {
        $assignment = Assignment::findOrFail($id);

        $filePath = $assignment->file;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Download with original filename
        return Storage::disk('public')->download(
            $filePath,
            basename($filePath)
        );
    }
    public function submissions(Assignment $assignment)
{
    // Get all students in the assignment's classroom
    $students = $assignment->classroom->students ?? collect();

    return view('teacher.assignments.submissions', compact('assignment', 'students'));
}


    // Student: submit assignment
    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();

        // Save to /storage/app/public/submissions/{student_id}/filename
        $storedPath = $file->storeAs(
            'submissions/' . auth()->id(),
            $originalName,
            'public'
        );

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
            ],
            [
                'file' => $storedPath,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Assignment submitted successfully.');
    }

    public function deleteSubmission(Assignment $assignment)
{
    $submission = $assignment->submissions()->where('student_id', auth()->id())->first();

    if ($submission) {
        // Delete file from storage
        if ($submission->file && Storage::disk('public')->exists($submission->file)) {
            Storage::disk('public')->delete($submission->file);
        }

        $submission->delete();
    }

    return back()->with('success', 'Submission deleted successfully.');
}


}
