<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
    'question_text',
    'question_type',
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'correct_answer',
    'marks_mcq',
    'marks_short',
    ];


    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
