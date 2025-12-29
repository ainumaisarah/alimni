<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // SHOW ALL QUESTIONS
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->get();
        return view('teacher.questions.index', compact('quiz', 'questions'));
    }

    // CREATE FORM
    public function create(Quiz $quiz)
    {
        return view('teacher.questions.create', compact('quiz'));
    }

    // STORE QUESTION
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text'   => 'required|string|max:1000',
            'question_type'   => 'required|in:mcq,short',
            // MCQ fields
            'option_a'        => 'required_if:question_type,mcq|max:255',
            'option_b'        => 'required_if:question_type,mcq|max:255',
            'option_c'        => 'nullable|max:255',
            'option_d'        => 'nullable|max:255',
            'correct_answer'  => 'required_if:question_type,mcq|in:A,B,C,D',
            // Short answer marks
            'marks_short'     => 'required_if:question_type,short|integer|min:1',
        ]);

        $questionType = $request->input('question_type');

        $data = [
            'question_text' => $request->question_text,
            'question_type' => $questionType,
            'quiz_id'       => $quiz->id,
        ];

        if ($questionType === 'mcq') {
            $data += [
                'option_a'      => $request->option_a,
                'option_b'      => $request->option_b,
                'option_c'      => $request->option_c,
                'option_d'      => $request->option_d,
                'correct_answer'=> $request->correct_answer,
                'marks_mcq'     => 1,                   // default for MCQ
                'marks_short'   => 2,                   // default for short answer
            ];
        } else { // short answer
            $data += [
                'option_a'      => null,
                'option_b'      => null,
                'option_c'      => null,
                'option_d'      => null,
                'correct_answer'=> null,
                'marks_mcq'     => 1,                   // default for MCQ
                'marks_short'   => $request->marks_short ?? 2,  // allocated marks
            ];
        }

        $quiz->questions()->create($data);

        // Reset student attempts
        $quiz->results()->delete();

        return redirect()
            ->route('teacher.questions.index', $quiz->id)
            ->with('success', 'Question added successfully!');
    }

    // EDIT FORM
    public function edit(Quiz $quiz, Question $question)
    {
        return view('teacher.questions.edit', compact('quiz', 'question'));
    }

    // UPDATE QUESTION
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $request->validate([
            'question_text'   => 'required|string|max:1000',
            'question_type'   => 'required|in:mcq,short',
            // MCQ fields
            'option_a'        => 'required_if:question_type,mcq|max:255',
            'option_b'        => 'required_if:question_type,mcq|max:255',
            'option_c'        => 'nullable|max:255',
            'option_d'        => 'nullable|max:255',
            'correct_answer'  => 'required_if:question_type,mcq|in:A,B,C,D',
            // Short answer marks
            'marks_short'     => 'required_if:question_type,short|integer|min:1',
        ]);

        $questionType = $request->input('question_type');

        if ($questionType === 'mcq') {
            $question->update([
                'question_text'   => $request->question_text,
                'question_type'   => 'mcq',
                'option_a'        => $request->option_a,
                'option_b'        => $request->option_b,
                'option_c'        => $request->option_c,
                'option_d'        => $request->option_d,
                'correct_answer'  => $request->correct_answer,
                'marks_mcq'       => 1,                 // default for MCQ
                'marks_short'     => 2,                 // default for short answer
            ]);
        } else { // short answer
            $question->update([
                'question_text'   => $request->question_text,
                'question_type'   => 'short',
                'option_a'        => null,
                'option_b'        => null,
                'option_c'        => null,
                'option_d'        => null,
                'correct_answer'  => null,
                'marks_mcq'       => 1,                 // default for MCQ
                'marks_short'     => $request->marks_short ?? 2,
            ]);
        }

        // Reset student attempts
        $quiz->results()->delete();

        return redirect()
            ->route('teacher.questions.index', $quiz->id)
            ->with('success', 'Question updated successfully!');
    }

    // DELETE QUESTION
    public function destroy(Quiz $quiz, Question $question)
    {
        $question->delete();

        // Reset student attempts
        $quiz->results()->delete();

        return redirect()
            ->route('teacher.questions.index', $quiz->id)
            ->with('success', 'Question deleted successfully!');
    }
}
