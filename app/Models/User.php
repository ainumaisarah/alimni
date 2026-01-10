<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, LogsActivity;

    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
        'consent_given_at',
        'parent_consented',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'consent_given_at' => 'datetime',
        'last_login_at' => 'datetime',
        'parent_consented' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['profile_photo_url'];

    /**
     * Spatie Activitylog options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'role', 'username', 'consent_given_at', 'parent_consented'])
            ->useLogName('user')
            ->logOnlyDirty();
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function teachingClasses()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_user', 'user_id', 'classroom_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

}
