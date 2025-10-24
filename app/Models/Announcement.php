<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'subject_id',
        'title',
        'message',
    ];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }
}
