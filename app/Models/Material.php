<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'classroom_id',
        'teacher_id',
    ];

    /* ===========================
       RELATIONSHIPS
    ============================ */

    // Material belongs to a classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Material belongs to a teacher (user)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Material has MANY files (PDF, DOC, VIDEO, etc.)
    public function files()
    {
        return $this->hasMany(MaterialFile::class)->orderBy('folder')->orderBy('created_at');
    }

}
