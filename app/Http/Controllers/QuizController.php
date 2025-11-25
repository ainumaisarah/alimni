<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizResult;

class QuizController extends Controller
{
    // Teacher: list own quizzes
    public function index()
    {
        $quizzes = Quiz::where('teacher_id', auth()->id())->get(); // only teacher's quizzes
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    // Show create form
    public function create()
    {
        // Only classrooms that this teacher teaches in
        $classrooms = Classroom::where('teacher_id', auth()->id())->get();

        // Check if a classroom_id is passed from the Classes page
        $classroomId = request()->query('classroom_id');

        return view('teacher.quizzes.create', compact('classrooms', 'classroomId'));
    }

    // Store a new quiz
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        // Save quiz
        $quiz = new Quiz();
        $quiz->title = $request->title;
        $quiz->description = $request->description;
        $quiz->classroom_id = $request->classroom_id;
        $quiz->teacher_id = auth()->id();
        $quiz->save();

        // Redirect back to classroom page
        return redirect()->route('classes.show', $request->classroom_id)
                        ->with('success', 'Quiz created successfully!');
    }


    // Delete a quiz
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

        // Get latest attempt
        $result = $quiz->results()->where('student_id', $student->id)->latest()->first();

        // Get all questions
        $questions = $quiz->questions()->get();

        // Determine answered questions
        $answeredCount = $result ? count($result->answers ?? []) : 0;
        $totalCount = $questions->count();

        // Determine if the student can retake
        $canRetake = !$result || ($answeredCount < $totalCount);

        return view('student.quizzes.show', compact('quiz', 'questions', 'result', 'canRetake'));
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

        // Show edit form for a quiz
    public function edit(Quiz $quiz)
    {
        $user = auth()->user();

        // Ensure the teacher owns this quiz
        if ($quiz->teacher_id !== $user->id) {
            abort(403, "Unauthorized - Only the owner can edit this quiz.");
        }

        // Only classrooms that this teacher teaches in
        $classrooms = Classroom::where('teacher_id', $user->id)->get();

        // Eager load questions for this quiz
        $quiz->load('questions');

        return view('teacher.quizzes.edit', compact('quiz', 'classrooms'));
    }

    // Update the quiz
   public function update(Request $request, Quiz $quiz)
    {
        $user = auth()->user();

        if ($quiz->teacher_id !== $user->id) {
            abort(403, "Unauthorized - Only the owner can update this quiz.");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        // Update quiz info
        $quiz->update($validated);

        // OPTION 1: Reset all student attempts
        if ($quiz->results()->exists()) {
            $quiz->results()->delete();
        }

        return redirect()->route('teacher.quizzes.index')
                        ->with('success', 'Quiz updated successfully! All student attempts have been reset.');
    }

    // Show quiz results for teacher
    public function results(Quiz $quiz)
    {
        // Get latest attempt per student
        $results = $quiz->results()
            ->with('student')
            ->get()
            ->groupBy('student_id')
            ->map(function ($attempts) {
                // Get the latest submission
                return $attempts->sortByDesc('created_at')->first();
            });

        $questions = $quiz->questions()->get();

        return view('teacher.quizzes.results', compact('quiz', 'results', 'questions'));
    }


}
