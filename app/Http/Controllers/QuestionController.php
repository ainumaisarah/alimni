<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // show questions for a quiz
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->get();
        return view('teacher.questions.index', compact('quiz', 'questions'));
    }

    // show create form for that quiz
    public function create(Quiz $quiz)
    {
        return view('teacher.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
{
    $request->validate([
        'question_text' => 'required|string|max:1000',
        'option_a' => 'required|string|max:255',
        'option_b' => 'required|string|max:255',
        'option_c' => 'required|string|max:255',
        'option_d' => 'required|string|max:255',
        'correct_answer' => 'required|in:A,B,C,D',
    ]);

    $quiz->questions()->create([
        'question_text' => $request->question_text,
        'option_a' => $request->option_a,
        'option_b' => $request->option_b,
        'option_c' => $request->option_c,
        'option_d' => $request->option_d,
        'correct_answer' => $request->correct_answer,
    ]);

    return redirect()->route('teacher.questions.index', $quiz->id)
                     ->with('success', 'Question added successfully!');
}

}
