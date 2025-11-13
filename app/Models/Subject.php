<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classroom_id',
        'teacher_id',
    ];

    /**
     * Each subject belongs to a classroom.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Each subject is taught by a teacher.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Subject.php
    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

}
