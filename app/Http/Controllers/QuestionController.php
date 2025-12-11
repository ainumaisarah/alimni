<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Show all questions for a quiz
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->get();
        return view('teacher.questions.index', compact('quiz', 'questions'));
    }

    // Show create form for a quiz
    public function create(Quiz $quiz)
    {
        return view('teacher.questions.create', compact('quiz'));
    }

    // Store new question
    public function store(Request $request, Quiz $quiz)
    {
        // Validation
        $request->validate([
            'question_text'  => 'required|string|max:1000',
            'question_type'  => 'required|in:mcq,short',

            'option_a'       => 'required_if:question_type,mcq|max:255',
            'option_b'       => 'required_if:question_type,mcq|max:255',
            'option_c'       => 'nullable|max:255',
            'option_d'       => 'nullable|max:255',
            'correct_answer' => 'required_if:question_type,mcq|in:A,B,C,D',

            'short_answer'   => 'required_if:question_type,short|max:255',
        ]);

        $data = [
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
        ];

        if ($request->question_type === 'mcq') {
            $data['option_a'] = $request->option_a;
            $data['option_b'] = $request->option_b;
            $data['option_c'] = $request->option_c;
            $data['option_d'] = $request->option_d;
            $data['correct_answer'] = $request->correct_answer;
            $data['short_answer'] = null;
        } else { // short answer
            $data['option_a'] = null;
            $data['option_b'] = null;
            $data['option_c'] = null;
            $data['option_d'] = null;
            $data['correct_answer'] = null;
            $data['short_answer'] = $request->short_answer;
        }

        $quiz->questions()->create($data);

        // Reset attempts
        $quiz->results()->delete();

        return redirect()
            ->route('teacher.questions.index', $quiz->id)
            ->with('success', 'Question added successfully!');
    }


    // Show edit form
    public function edit(Quiz $quiz, Question $question)
    {
        return view('teacher.questions.edit', compact('quiz', 'question'));
    }


    // Update question
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:mcq,short',
            'option_a' => 'required_if:question_type,mcq|max:255',
            'option_b' => 'required_if:question_type,mcq|max:255',
            'option_c' => 'nullable|max:255',
            'option_d' => 'nullable|max:255',
            'correct_answer' => 'required_if:question_type,mcq|in:A,B,C,D',
            'short_answer' => 'required_if:question_type,short|max:255',
        ]);

        $question->update([
            'question_text'  => $request->question_text,
            'question_type'  => $request->question_type,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'short_answer'   => $request->short_answer,
        ]);

        // Reset attempts
        $question->quiz->results()->delete();

        return redirect()->route('teacher.questions.index', $question->quiz->id)
                        ->with('success', 'Question updated successfully!');
    }


    // Delete question
    public function destroy(Quiz $quiz, Question $question)
    {
        $question->delete();
        return redirect()->route('teacher.questions.index', $quiz->id)
                        ->with('success', 'Question deleted successfully!');
    }
}
