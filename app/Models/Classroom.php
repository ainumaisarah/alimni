<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    // ✅ Add the fields you want to allow for mass assignment
    protected $fillable = [
        'name',
        'description',
        'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class);
    }

    public function students()
    {
        return $this->hasMany(\App\Models\User::class)->where('role', 'student');
    }

    public function subjects()
    {
    return $this->hasMany(Subject::class, 'classroom_id');
    }


}
