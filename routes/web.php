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
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TeacherHomeController;
use App\Http\Controllers\StudentHomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassPageController;
use App\Http\Controllers\Admin\StudentImportController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\AssignmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard routes (authenticated users)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Teacher dashboard
    Route::middleware([IsTeacher::class])->group(function () {
        Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
        Route::get('/teacher/home', [TeacherHomeController::class, 'index'])->name('teacher.home');
    });

    // Student dashboard
    Route::middleware([IsStudent::class])->group(function () {
        Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/student/home', [StudentHomeController::class, 'index'])->name('student.home');
    });
});

// Admin Routes
Route::middleware([IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [ScheduleController::class, 'home'])->name('home');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('classrooms', ClassroomController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

    // Enrollment
    Route::get('users/{user}/enroll', [UserController::class, 'edit'])->name('users.enroll');
    Route::put('users/{user}/enroll', [UserController::class, 'update'])->name('users.enroll.update');
    Route::get('classroom-enroll', [UserController::class, 'showClassroomEnrollForm'])->name('classroom.enroll');
    Route::post('classroom-enroll/{classroom}', [UserController::class, 'enrollStudentsToClassroom'])->name('classroom.enroll.submit');

    // Overview
    Route::get('classroom-overview', [ClassroomController::class, 'overview'])->name('classrooms.overview');

    Route::get('students/import', [StudentImportController::class, 'showForm'])->name('students.import');
    Route::post('students/import', [StudentImportController::class, 'import'])->name('students.import.post');
});

// Chat routes (shared)
Route::middleware(['auth'])->group(function () {
    Route::get('chat', [ChatController::class, 'list'])->name('chat.list');
    Route::get('chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('chat/{user}', [ChatController::class, 'send'])->name('chat.send');
});

// Teacher Materials & Quizzes
Route::middleware(['auth', IsTeacher::class])->prefix('teacher')->name('teacher.')->group(function () {
    // Materials
    Route::get('materials', [MaterialController::class, 'teacherIndex'])->name('materials.index');
    Route::get('materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
    Route::delete('materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');

    // Quizzes & Questions
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');

    // Announcements
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
});

// Student Materials & Quizzes
Route::middleware(['auth', IsStudent::class])->prefix('student')->name('student.')->group(function () {
    Route::get('materials', [MaterialController::class, 'studentIndex'])->name('materials.index');
    Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::get('quizzes', [QuizController::class, 'studentIndex'])->name('quizzes.index');
    Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    Route::get('announcements', [AnnouncementController::class, 'studentIndex'])->name('announcements.index');
});

// Classes / Teams-style pages
Route::middleware(['auth'])->prefix('classes')->group(function () {
    // List all classes
    Route::get('/', [ClassPageController::class, 'index'])->name('classes.index');

    // Specific routes first
    Route::get('/{class}/materials', [ClassPageController::class, 'materials'])->name('classes.materials');
    Route::get('/{class}/assignment', [ClassPageController::class, 'assignment'])->name('classes.assignment');
    Route::get('/{class}/quiz', [ClassPageController::class, 'quiz'])->name('classes.quiz');
    Route::get('/{class}/channel', [ChannelController::class, 'show'])->name('channel.show');

    // Posts & Comments
    Route::post('/{class}/post', [ClassPageController::class, 'storePost'])->name('channel.post');
    Route::post('/post/{post}/comment', [ClassPageController::class, 'storeComment'])->name('channel.comment');

    // Generic class page (catch-all) - must be last
    Route::get('/{class}', [ClassPageController::class, 'showClass'])->name('classes.show');
});

//delete/edit post
Route::put('/channel/post/{post}', [ClassPageController::class, 'update'])->name('channel.post.update');
Route::delete('/channel/post/{post}', [ClassPageController::class, 'destroy'])->name('channel.post.delete');

// Update comment
Route::put('/channel/comment/{comment}', [ClassPageController::class, 'updateComment'])->name('channel.comment.update');

// Delete comment
Route::delete('/channel/comment/{comment}', [ClassPageController::class, 'destroyComment'])->name('channel.comment.delete');

// Materials
Route::get('/classes/{class}/materials', [ClassPageController::class, 'materials'])
    ->name('classes.materials');

// Quizzes
Route::get('/classes/{class}/quizzes', [ClassPageController::class, 'quizzes'])
    ->name('classes.quizzes');
// Assignment page for a class
Route::get('/classes/{class}/assignment', [ClassPageController::class, 'assignment'])
    ->name('classes.assignment');


//Assignment
// Teacher routes
Route::middleware(['auth', IsTeacher::class])->group(function () {
    Route::get('/assignments/create/{classroom_id}', [AssignmentController::class, 'create'])->name('teacher.assignments.create');
    Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('teacher.assignments.store');
    Route::get('/assignments/edit/{assignment}', [AssignmentController::class, 'edit'])->name('teacher.assignments.edit');
    Route::put('/assignments/update/{assignment}', [AssignmentController::class, 'update'])->name('teacher.assignments.update');
    Route::delete('/assignments/delete/{assignment}', [AssignmentController::class, 'destroy'])->name('teacher.assignments.destroy');
});
;

Route::middleware(['auth', \App\Http\Middleware\IsTeacher::class])->prefix('teacher')->group(function () {
    Route::get('/assignments/download/{assignment}', [AssignmentController::class, 'download'])
        ->name('teacher.assignments.download');
});

// Student routes
Route::middleware(['auth', IsStudent::class])->group(function () {
    Route::get('/assignments/download/{assignment}', [AssignmentController::class, 'download'])->name('student.assignments.download');
    Route::post('/assignments/submit/{assignment}', [AssignmentController::class, 'submit'])->name('student.assignments.submit');
});
