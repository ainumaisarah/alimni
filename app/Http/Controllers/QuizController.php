<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizResult;

class QuizController extends Controller
{
    public function index()
    {
    $quizzes = Quiz::where('teacher_id', auth()->id())->get(); // only teacher's quizzes
    return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
    $teacherId = Auth::id();

    // Only subjects taught by this teacher
    $subjects = Subject::where('teacher_id', $teacherId)->get();

    // Only classrooms that this teacher teaches in
    $classrooms = Classroom::where('teacher_id', $teacherId)->get();

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

// List all quizzes for the student
    public function studentIndex()
    {
        $student = auth()->user();
        $quizzes = Quiz::where('classroom_id', $student->classroom_id)->get();
        return view('student.quizzes.index', compact('quizzes'));
    }

    // Show the quiz questions
    public function show(Quiz $quiz)
    {
    $student = auth()->user();

    // Get previous attempt
    $result = $quiz->results()->where('student_id', $student->id)->latest()->first();

    $questions = $quiz->questions()->get();

    return view('student.quizzes.show', compact('quiz', 'questions', 'result'));
    }
    // Handle quiz submission
    public function submit(Request $request, Quiz $quiz)
    {
    $answers = $request->input('answers', []);
    $student = auth()->user();

    $score = 0;

    foreach ($quiz->questions as $question) {
        $studentAnswer = $answers[$question->id] ?? null;
        if ($studentAnswer && strtoupper($studentAnswer) === strtoupper($question->correct_answer)) {
            $score++;
        }
    }

    // Save to quiz_results table
    QuizResult::create([
        'quiz_id' => $quiz->id,
        'student_id' => $student->id,
        'answers' => $answers,
        'score' => $score,
    ]);

    return redirect()->route('student.quizzes.index')
                     ->with('success', "You scored $score out of ".$quiz->questions->count()."!");
    }

}
