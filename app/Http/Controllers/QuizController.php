<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('teacher_id', Auth::id())->with('subject', 'classroom')->get();
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        return view('teacher.quizzes.create', compact('subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $validated['teacher_id'] = Auth::id();

        Quiz::create($validated);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz created successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}
