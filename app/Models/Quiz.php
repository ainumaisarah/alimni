<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',      // in minutes
        'open_at',       // datetime when quiz opens
        'due_at',      // datetime when quiz closes
        'classroom_id',  // related classroom
        'subject_id',    // optional subject
        'max_attempts'
    ];

    protected $dates = [
        'open_at',
        'due_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
    'open_at' => 'datetime',
    'due_at'  => 'datetime',

    'answers' => 'array', //quiz:store answers as jason
    ];

    protected $primaryKey = 'id'; // should match the DB column



    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
    return $this->hasMany(QuizResult::class);
    }

}
