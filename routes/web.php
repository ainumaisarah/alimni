<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\IsStudent;
use App\Http\Middleware\IsTeacher;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Models\Schedule;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//dashboardd//

Route::middleware([IsTeacher::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
});

Route::middleware([IsStudent::class])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});

//dashboardd//

//homepagee//

Route::middleware([IsTeacher::class])->group(function () {
    Route::get('/teacher/home', function () {
        return view('teacher.home');
    })->name('teacher.home');
});

Route::middleware([IsStudent::class])->group(function () {
    Route::get('/student/home', function () {
        return view('student.home');
    })->name('student.home');
});

//homepagee//

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

//Admin Routes
Route::middleware([IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin home (shows schedule list)
    Route::get('/home', [\App\Http\Controllers\Admin\ScheduleController::class, 'home'])->name('home');

    // Admin dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Classroom management
    Route::resource('classrooms', ClassroomController::class);

    // Schedule management
    Route::resource('schedules', ScheduleController::class);

    // User management
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update','destroy']);

    // Individual student enrollment
    Route::get('users/{user}/enroll', [UserController::class, 'edit'])->name('users.enroll');
    Route::put('users/{user}/enroll', [UserController::class, 'update'])->name('users.enroll.update');

    // Bulk classroom enrollment
    Route::get('classroom-enroll', [UserController::class, 'showClassroomEnrollForm'])->name('classroom.enroll');
    Route::post('classroom-enroll/{classroom}', [UserController::class, 'enrollStudentsToClassroom'])->name('classroom.enroll.submit');

    // Overview
    Route::get('classroom-overview', [ClassroomController::class, 'overview'])->name('classrooms.overview');
});


