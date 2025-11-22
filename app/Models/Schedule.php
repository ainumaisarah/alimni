<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'teacher_id');
    }

    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function schedules()
    {
        if ($this->role === 'Teacher') {
            return $this->hasMany(Schedule::class, 'teacher_id');
        } else {
            return $this->hasManyThrough(
                Schedule::class,
                Classroom::class,
                'id', // Classroom primary key
                'classroom_id', // Schedule foreign key
                'classroom_id', // User's classroom_id
                'id'
            );
        }
    }


}
