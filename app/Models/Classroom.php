<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class);
    }

    // Students → many-to-many
    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_user')->where('role', 'student');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'classroom_id');
    }

    public function materials()
    {
        return $this->hasMany(\App\Models\Material::class);
    }

    public function quizzes()
    {
        return $this->hasMany(\App\Models\Quiz::class);
    }
}

