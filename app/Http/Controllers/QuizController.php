<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizResult;
// --------------------------
// Student-facing methods
// --------------------------
use App\Models\Attempt;
use App\Models\Answer;


class QuizController extends Controller
{
    // Teacher: list own quizzes
    public function index($classroomId)
{
    $class = Classroom::findOrFail($classroomId);

    $quizzes = Quiz::where('teacher_id', auth()->id())
                   ->where('classroom_id', $classroomId)
                   ->latest() // latest quiz first
                   ->get();

    return view('classes.quizzes', compact('class', 'quizzes'));
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
            'duration' => 'nullable|integer|min:1',
            'open_at' => 'nullable|date',
            'due_at' => 'nullable|date|after_or_equal:open_at',
        ]);

        $quiz = new Quiz();
        $quiz->title = $request->title;
        $quiz->description = $request->description;
        $quiz->classroom_id = $request->classroom_id;
        $quiz->teacher_id = auth()->id();
        $quiz->show_answers = $request->has('show_answers'); // checkbox
        $quiz->duration = $request->duration;
        $quiz->open_at = $request->open_at;
        $quiz->due_at = $request->due_at;
        $quiz->save();

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                 ->with('success', 'Quiz created successfully!');
    }


    // Delete a quiz
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                        ->with('success', 'Quiz deleted successfully.');
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
            'description' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'duration' => 'nullable|integer|min:1',
            'open_at' => 'nullable|date',
            'due_at' => 'nullable|date|after_or_equal:open_at',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'classroom_id' => $validated['classroom_id'],
            'show_answers' => $request->has('show_answers'),
            'duration' => $validated['duration'] ?? null,
            'open_at' => $validated['open_at'] ?? null,
            'due_at' => $validated['due_at'] ?? null,
        ]);

        // Reset all student attempts if needed
        if ($quiz->results()->exists()) {
            $quiz->results()->delete();
        }

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                 ->with('success', 'Quiz updated successfully! All student attempts have been reset.');
    }

// Show quiz results for teacher
    // Show all quiz results for a teacher
// Show quiz results for teacher (all attempts)
// Show quiz results summary for teacher
public function results(Quiz $quiz)
{
    $class = $quiz->classroom;

    // Get all students in the class
    $students = $class->students()->orderBy('name')->get();

    // Prepare summary: latest attempt & total attempts per student
    $studentResults = $students->map(function ($student) use ($quiz) {
        $attempts = $quiz->results()
                         ->where('student_id', $student->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        $latestScore = $attempts->first()?->score ?? null; // null if no attempt
        $totalAttempts = $attempts->count();

        return [
            'student' => $student,
            'latestScore' => $latestScore,
            'totalAttempts' => $totalAttempts,
            'attempts' => $attempts,
        ];
    });

    return view('teacher.quizzes.results', compact('quiz', 'studentResults'));
}



// Review a single student attempt (teacher)
public function teacherReview(Quiz $quiz, QuizResult $result)
{
    // Make sure the result belongs to this quiz
    if ($result->quiz_id !== $quiz->id) {
        abort(404, 'Result does not belong to this quiz.');
    }

    $questions = $quiz->questions;

    return view('teacher.quizzes.review', compact('quiz', 'result', 'questions'));
}


////////////----- S T U D E N T   P A R T -----////////////////
/*public function studentIndex()
{
    $student = auth()->user();

    $classroomIds = $student->classrooms()->pluck('classrooms.id');

    $quizzes = Quiz::whereIn('classroom_id', $classroomIds)
                   ->with(['results' => function($q) use ($student){
                        $q->where('student_id', $student->id);
                   }])
                   ->get();

    return view('student.quizzes.index', compact('quizzes'));
}*/

// Single quiz dashboard (index for one quiz)
// Show single quiz dashboard (attempts, retake button)
public function studentQuiz(Quiz $quiz)
{
    $student = auth()->user();

    // Load only this student's attempts
    $quiz->load(['results' => function($q) use ($student) {
        $q->where('student_id', $student->id);
    }]);

    $attempts = $quiz->results;
    $attemptsCount = $attempts->count();
    $maxAttempts = 3;

    return view('student.quizzes.index', compact('quiz', 'attempts', 'attemptsCount', 'maxAttempts'));
}

// Show the quiz questions for student to attempt
public function showStudent(Quiz $quiz)
{
    $student = auth()->user();
    $attemptsCount = $quiz->results()->where('student_id', $student->id)->count();
    $maxAttempts = 3;

    if ($attemptsCount >= $maxAttempts) {
        return redirect()->route('student.quizzes.single', $quiz->id)
            ->with('error', 'You have reached the maximum attempts.');
    }

    $questions = $quiz->questions()->get();

    return view('student.quizzes.show', compact('quiz', 'questions', 'attemptsCount', 'maxAttempts'));
}

// Submit quiz answers
public function submit(Request $request, Quiz $quiz)
{
    $student = auth()->user();
    $attemptsCount = $quiz->results()->where('student_id', $student->id)->count();

    if($attemptsCount >= 3){
        return redirect()->route('student.quizzes.single', $quiz->id)
            ->with('error', 'You cannot attempt this quiz more than 3 times.');
    }

    $answersInput = $request->input('answers', []);
    $score = 0;
    $questions = $quiz->questions()->get();

    foreach($questions as $question){
        $studentAnswer = strtoupper(trim($answersInput[$question->id] ?? ''));
        $correctAnswer = strtoupper(trim($question->correct_answer ?? ''));

        if($studentAnswer === $correctAnswer){
            $score++;
        }
    }

    $attemptNumber = $attemptsCount + 1;

    $quiz->results()->create([
        'quiz_id' => $quiz->id, // add this
        'student_id' => $student->id,
        'answers' => $answersInput,
        'score' => round(($score / $questions->count()) * 100, 2),
        'attempt_number' => $attemptNumber,
    ]);


    return redirect()->route('student.quizzes.single', $quiz->id)
        ->with('success', 'Quiz submitted successfully!');
}

// Review a previous attempt
public function review(Quiz $quiz, $resultId)
{
    $student = auth()->user();

    $result = $quiz->results()->where('student_id', $student->id)->findOrFail($resultId);
    $answers = $result->answers;
    $questions = $quiz->questions()->get()->keyBy('id');

    return view('student.quizzes.review', compact('quiz', 'result', 'answers', 'questions'));
}



}
