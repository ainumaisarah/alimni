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
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TeacherHomeController;
use App\Http\Controllers\StudentHomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassPageController;


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
    Route::get('/teacher/home', [TeacherHomeController::class, 'index'])->name('teacher.home');
});

Route::middleware([IsStudent::class])->group(function () {
    Route::get('/student/home', [StudentHomeController::class, 'index'])->name('student.home');
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

        // QUIZZES
        Route::get('quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::get('quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('quizzes', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
        Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');

        // QUESTIONS
        Route::get('quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

       // Announcements (Teacher)
        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    });

// Student routes (only students)
    Route::middleware([IsStudent::class])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Student materials
        Route::get('materials', [MaterialController::class, 'studentIndex'])->name('materials.index');
        Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');

         // Student quizzes
        Route::get('quizzes', [QuizController::class, 'studentIndex'])->name('quizzes.index');
        Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
        Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

       //annoucements
        Route::get('announcements', [AnnouncementController::class, 'studentIndex'])->name('announcements.index');
    });

    Route::middleware(['auth'])->group(function () {
    // Chat list
    Route::get('chat', [ChatController::class, 'list'])->name('chat.list');
    // Chat with specific user
    Route::get('chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    // Send message
    Route::post('chat/{user}', [ChatController::class, 'send'])->name('chat.send');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/classes', [ClassPageController::class, 'index'])->name('classes.index');
});

// Teacher class page
Route::get('/teacher/classes', [ClassPageController::class, 'index'])->name('teacher.classes.index');
Route::get('/teacher/classes/subject/{subjectId}', [ClassPageController::class, 'showSubject'])->name('teacher.classes.subject.show');

// Student class page
Route::get('/student/classes', [ClassPageController::class, 'index'])->name('student.classes.index');
Route::get('/student/classes/subject/{subjectId}', [ClassPageController::class, 'showSubject'])->name('student.classes.subject.show');


