<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    /* ===============================
       TEACHER: CREATE ASSIGNMENT FORM
    ================================ */
    public function create($classroom_id)
    {
        return view('teacher.assignments.create', compact('classroom_id'));
    }

    /* ===============================
       TEACHER: STORE ASSIGNMENT
    ================================ */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:20480',
            'due_at' => 'nullable|date',
            'allow_late_submission' => 'nullable|boolean',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $filename, 'public');
        }

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
            'allow_late_submission' => $request->has('allow_late_submission'),
        ]);

        // Log creation
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'assignment_id' => $assignment->id,
                'class_id' => $assignment->classroom_id,
            ])
            ->log('Created assignment');

        return redirect()
            ->route('classes.assignment', $request->classroom_id)
            ->with('success', 'Assignment created successfully!');
    }

    /* ===============================
       TEACHER: EDIT ASSIGNMENT FORM
    ================================ */
    public function edit(Assignment $assignment)
    {
        return view('teacher.assignments.edit', compact('assignment'));
    }

    /* ===============================
       TEACHER: UPDATE ASSIGNMENT
    ================================ */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480', // 20MB
            'due_at' => 'nullable|date',
            'allow_late_submission' => 'nullable|boolean',
        ]);

        // Replace file if new uploaded
        if ($request->hasFile('file')) {
            if ($assignment->file && Storage::disk('public')->exists($assignment->file)) {
                Storage::disk('public')->delete($assignment->file);
            }

            $originalName = $request->file('file')->getClientOriginalName();
            $storedPath = $request->file('file')->storeAs('assignments', $originalName, 'public');
            $assignment->file = $storedPath;
        }

        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->due_at = $request->due_at
            ? Carbon::parse($request->due_at, 'Asia/Kuala_Lumpur')->setTimezone('UTC')
            : null;
        $assignment->allow_late_submission = $request->has('allow_late_submission');

        $assignment->save();

        // Log update
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'assignment_id' => $assignment->id,
                'class_id' => $assignment->classroom_id,
            ])
            ->log('Updated assignment');

        return redirect()
            ->route('classes.assignment', $assignment->classroom_id)
            ->with('success', 'Assignment updated successfully!');
    }

    /* ===============================
       TEACHER: DELETE ASSIGNMENT
    ================================ */
    public function destroy(Assignment $assignment)
    {
        if ($assignment->file && Storage::disk('public')->exists($assignment->file)) {
            Storage::disk('public')->delete($assignment->file);
        }

        $assignment->delete();

        // Log deletion
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'assignment_id' => $assignment->id,
                'class_id' => $assignment->classroom_id,
            ])
            ->log('Deleted assignment');

        return back()->with('success', 'Assignment deleted.');
    }

    /* ===============================
       TEACHER & STUDENT: DOWNLOAD FILE
    ================================ */
    public function download($id)
    {
        $assignment = Assignment::findOrFail($id);

        if (!$assignment->file || !Storage::disk('public')->exists($assignment->file)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $assignment->file,
            basename($assignment->file)
        );
    }

    /* ===============================
       TEACHER: VIEW SUBMISSIONS
    ================================ */
    public function submissions(Assignment $assignment)
    {
        $students = $assignment->classroom->students ?? collect();
        return view('teacher.assignments.submissions', compact('assignment', 'students'));
    }

    /* ===============================
       STUDENT: SUBMIT ASSIGNMENT
    ================================ */
    public function submit(Request $request, Assignment $assignment)
    {
        if ($assignment->due_at) {
            $dueAt = Carbon::parse($assignment->due_at)->timezone('Asia/Kuala_Lumpur');
            if (now('Asia/Kuala_Lumpur')->gt($dueAt) && !$assignment->allow_late_submission) {
                return back()->withErrors(['file' => 'Submission is closed for this assignment.']);
            }
        }

        $request->validate(['file' => 'required|file|max:10240']); // 10MB

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedPath = $file->storeAs('submissions/' . auth()->id(), $originalName, 'public');

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => auth()->id()],
            ['file' => $storedPath, 'submitted_at' => now()]
        );

        // Log submission
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'assignment_id' => $assignment->id,
                'file_name' => $file->getClientOriginalName(),
                'class_id' => $assignment->classroom_id,
            ])
            ->log('Submitted assignment');

        return back()->with('success', 'Assignment submitted successfully.');
    }

    /* ===============================
       STUDENT: DELETE SUBMISSION
    ================================ */
    public function deleteSubmission(Assignment $assignment)
    {
        $submission = $assignment->submissions()->where('student_id', auth()->id())->first();

        if ($submission) {
            if ($submission->file && Storage::disk('public')->exists($submission->file)) {
                Storage::disk('public')->delete($submission->file);
            }
            $submission->delete();

            // Log deletion
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'assignment_id' => $assignment->id,
                    'student_id' => auth()->id(),
                    'class_id' => $assignment->classroom_id,
                ])
                ->log('Deleted assignment submission');
        }

        return back()->with('success', 'Submission deleted successfully.');
    }
}
