<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // =========================
    // TEACHER METHODS
    // =========================

    // List quizzes for a classroom (teacher)
    public function index($classroomId)
    {
        $class = Classroom::findOrFail($classroomId);

        $quizzes = Quiz::where('teacher_id', auth()->id())
                       ->where('classroom_id', $classroomId)
                       ->latest()
                       ->get();

        return view('classes.quizzes', compact('class', 'quizzes'));
    }

    // Show create form
    public function create()
    {
        $classrooms = Classroom::where('teacher_id', auth()->id())->get();
        $classroomId = request()->query('classroom_id');

        return view('teacher.quizzes.create', compact('classrooms', 'classroomId'));
    }

    // Store new quiz
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'duration' => 'nullable|integer|min:1',
            'open_at' => 'nullable|date',
            'due_at' => 'nullable|date|after_or_equal:open_at',
            'max_attempts' => 'required|integer|min:1|max:3',
        ]);

        $quiz = new Quiz();
        $quiz->title = $request->title;
        $quiz->description = $request->description;
        $quiz->classroom_id = $request->classroom_id;
        $quiz->teacher_id = auth()->id();
        $quiz->show_answers = $request->has('show_answers');
        $quiz->max_attempts = $request->max_attempts;
        $quiz->duration = $request->duration;
        $quiz->open_at = $request->open_at;
        $quiz->due_at = $request->due_at;
        $quiz->save();

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                 ->with('success', 'Quiz created successfully!');
    }

    // Show edit form
    public function edit(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) {
            abort(403, "Unauthorized");
        }

        $classrooms = Classroom::where('teacher_id', auth()->id())->get();
        $quiz->load('questions');

        return view('teacher.quizzes.edit', compact('quiz', 'classrooms'));
    }

    // Update quiz
    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'duration' => 'nullable|integer|min:1',
            'open_at' => 'nullable|date',
            'due_at' => 'nullable|date|after_or_equal:open_at',
            'max_attempts' => 'required|integer|min:1|max:3',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'classroom_id' => $validated['classroom_id'],
            'show_answers' => $request->has('show_answers'),
            'duration' => $validated['duration'] ?? null,
            'open_at' => $validated['open_at'] ?? null,
            'due_at' => $validated['due_at'] ?? null,
            'max_attempts' => $validated['max_attempts'],
        ]);

        // Reset all student attempts
        $quiz->results()->delete();

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                 ->with('success', 'Quiz updated successfully! Student attempts reset.');
    }

    // Delete quiz
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('classes.quizzes', $quiz->classroom_id)
                        ->with('success', 'Quiz deleted successfully.');
    }

    // Show quiz results for teacher
    public function results(Quiz $quiz)
    {
        $class = $quiz->classroom;
        $students = $class->students()->orderBy('name')->get();

        $studentResults = $students->map(function ($student) use ($quiz) {
            $attempts = $quiz->results()->where('student_id', $student->id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

            return [
                'student' => $student,
                'latestScore' => $attempts->first()?->score ?? null,
                'totalAttempts' => $attempts->count(),
                'attempts' => $attempts,
            ];
        });

        return view('teacher.quizzes.results', compact('quiz', 'studentResults'));
    }

    // Review a student attempt
    public function teacherReview(Quiz $quiz, QuizResult $result)
    {
        if ($result->quiz_id !== $quiz->id) {
            abort(404);
        }

        $questions = $quiz->questions;

        return view('teacher.quizzes.review', compact('quiz', 'result', 'questions'));
    }

    // Grade short answers
    public function submitGrades(Request $request, Quiz $quiz, QuizResult $result)
    {
        if ($result->quiz_id !== $quiz->id) {
            abort(404);
        }

        $questions = $quiz->questions;
        $answers = $result->answers ?? [];

        $earnedMarks = 0;
        $totalMarks = 0;

        $rules = [];

        // Prepare validation rules for short answer grades
        foreach ($questions as $question) {
            if ($question->question_type === 'short') {
                $rules["grades.{$question->id}"] = "required|numeric|min:0|max:{$question->marks_short}";
            }
        }

        $validated = $request->validate($rules);
        $grades = $validated['grades'] ?? [];

        foreach ($questions as $question) {
            if ($question->question_type === 'mcq') {
                $totalMarks += $question->marks_mcq;
                $ans = $answers[$question->id] ?? null;
                if ($ans !== null && strtoupper($ans) === strtoupper($question->correct_answer)) {
                    $earnedMarks += $question->marks_mcq;
                    $answers[$question->id.'_marks'] = $question->marks_mcq;
                } else {
                    $answers[$question->id.'_marks'] = 0;
                }
            }

            if ($question->question_type === 'short') {
                $totalMarks += $question->marks_short;
                $mark = $grades[$question->id] ?? 0;
                $earnedMarks += $mark;
                $answers[$question->id.'_marks'] = $mark;
            }
        }

        $finalScore = $totalMarks > 0 ? round(($earnedMarks / $totalMarks) * 100, 2) : 0;

        $result->update([
            'answers' => $answers,
            'score' => $finalScore
        ]);

        return redirect()->route('teacher.quizzes.review', [$quiz->id, $result->id])
                        ->with('success', 'Grades saved successfully.');
    }

    // =========================
// TEACHER SHOW QUIZ
// =========================

/**
 * Show the quiz to the teacher (read-only view, like student view)
 */
public function showQuiz(Quiz $quiz)
{
    // Make sure the authenticated teacher owns this quiz
    if ($quiz->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized access to this quiz.');
    }

    // Get all questions
    $questions = $quiz->questions()->get();

    // Return a view similar to student show
    return view('teacher.quizzes.show', compact('quiz', 'questions'));
}


    // =========================
    // STUDENT METHODS
    // =========================

    // Show quizzes for student in one classroom
    public function studentQuiz(Quiz $quiz)
    {
        $student = auth()->user();
        $attempts = $quiz->results()->where('student_id', $student->id)->get();
        $attemptsCount = $attempts->count();
        $maxAttempts = $quiz->max_attempts;

        $questions = $quiz->questions()->get();

        return view('student.quizzes.index', compact('quiz', 'questions', 'attempts', 'attemptsCount', 'maxAttempts'));
    }

    // Show single quiz for student to attempt
    public function showStudent(Quiz $quiz)
    {
        $student = auth()->user();

        if ($quiz->open_at && $quiz->open_at->isFuture()) {
            return redirect()->route('student.quizzes.single', $quiz->id)
                ->with('error', 'This quiz is not open yet.');
        }

        if ($quiz->due_at && $quiz->due_at->isPast()) {
            return redirect()->route('student.quizzes.single', $quiz->id)
                ->with('error', 'This quiz is already closed.');
        }

        $attemptsCount = $quiz->results()->where('student_id', $student->id)->count();
        $maxAttempts = $quiz->max_attempts;

        if ($attemptsCount >= $maxAttempts) {
            return redirect()->route('student.quizzes.single', $quiz->id)
                ->with('error', 'You have reached the maximum attempts.');
        }

        $questions = $quiz->questions()->get();

        return view('student.quizzes.show', compact('quiz', 'questions', 'attemptsCount', 'maxAttempts'));
    }

    // Submit quiz answers (MCQ auto-marked)
// Submit quiz answers (MCQ auto-marked)
    public function submit(Request $request, Quiz $quiz)
    {
        $student = auth()->user();
        $attemptsCount = $quiz->results()->where('student_id', $student->id)->count();
        $maxAttempts = $quiz->max_attempts;

        if($attemptsCount >= $maxAttempts){
            return redirect()->route('student.quizzes.single', $quiz->id)
                ->with('error', 'You cannot attempt this quiz more than 3 times.');
        }

        $answersInput = $request->input('answers', []);
        $earnedMarks = 0;
        $totalMarks = 0;
        $questions = $quiz->questions()->get();

        $answersOutput = []; // To store marks for each question

        foreach($questions as $question){
            if ($question->question_type === 'mcq') {
                $totalMarks += $question->marks_mcq;
                $studentAnswer = strtoupper(trim($answersInput[$question->id] ?? ''));
                $correctAnswer = strtoupper(trim($question->correct_answer ?? ''));

                if($studentAnswer === $correctAnswer){
                    $earnedMarks += $question->marks_mcq;
                    $answersOutput[$question->id.'_marks'] = $question->marks_mcq;
                } else {
                    $answersOutput[$question->id.'_marks'] = 0;
                }
            }

            if ($question->question_type === 'short') {
                $totalMarks += $question->marks_short;
                // Short answers will be graded by teacher later
                $answersOutput[$question->id.'_marks'] = null;
            }
        }

        $attemptNumber = $attemptsCount + 1;

        $scorePercent = $totalMarks > 0 ? round(($earnedMarks / $totalMarks) * 100, 2) : 0;

        $quiz->results()->create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'answers' => $answersInput,
            'score' => $scorePercent,
            'attempt_number' => $attemptNumber,
        ]);

        return redirect()->route('student.quizzes.single', $quiz->id)
            ->with('success', 'Quiz submitted successfully! Short answers will be graded by teacher.');
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
