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
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;


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

    // Subject management ✅
    Route::resource('subjects', SubjectController::class);


});

//material routes
Route::middleware(['auth'])->group(function () {
    Route::get('/materials', [\App\Http\Controllers\MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [\App\Http\Controllers\MaterialController::class, 'create'])->middleware('is_teacher')->name('materials.create');
    Route::post('/materials', [\App\Http\Controllers\MaterialController::class, 'store'])->middleware('is_teacher')->name('materials.store');
    Route::get('/materials/{id}/download', [\App\Http\Controllers\MaterialController::class, 'download'])->name('materials.download');
});

// Teacher routes (only teachers)
Route::middleware([IsTeacher::class])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // =====================
        // 📚 MATERIALS SECTION
        // =====================
        Route::get('materials', [MaterialController::class, 'teacherIndex'])->name('materials.index');
        Route::get('materials/create', [MaterialController::class, 'create'])->name('materials.create');
        Route::post('materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::delete('materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
        Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');

        // =====================
        // 🧩 QUIZZES SECTION
        // =====================
        Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');           // List all quizzes
        Route::get('quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');  // Create form
        Route::post('quizzes', [QuizController::class, 'store'])->name('quizzes.store');          // Save quiz
        Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit'); // Edit form
        Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');  // Update
        Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy'); // Delete

        // =====================
        // ❓ QUESTIONS SECTION
        // =====================
        Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
        Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });




// Student routes (only students)
Route::middleware([IsStudent::class])->prefix('student')->name('student.')->group(function () {
    Route::get('materials', [MaterialController::class, 'studentIndex'])->name('materials.index');
    Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
});


