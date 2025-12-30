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
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AuditReportController;


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
//Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
//Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.updatePhoto');
    Route::put('/profile/photo/remove', [ProfileController::class, 'removeProfilePhoto'])->name('profile.removePhoto');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
// Authenticated routes
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
    Route::delete('classrooms/{classroom}/students/{student}', [UserController::class, 'unenrollStudent'])->name('classroom.unenroll');

    Route::get('classroom-overview', [ClassroomController::class, 'overview'])->name('classrooms.overview');

    Route::get('students/import', [StudentImportController::class, 'showForm'])->name('students.import');
    Route::post('students/import', [StudentImportController::class, 'import'])->name('students.import.post');

    Route::get('teachers/import', [TeacherController::class, 'showForm'])->name('teachers.import.form');
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::get('/teachers', [TeacherController::class, 'index']) ->name('teachers.index');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']) ->name('teachers.destroy');
});

// Chat routes
Route::middleware(['auth'])->group(function () {
    Route::get('chat', [ChatController::class, 'list'])->name('chat.list');
    Route::get('chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('chat/{user}', [ChatController::class, 'send'])->name('chat.send');
});

// Teacher routes
Route::middleware(['auth', IsTeacher::class])->prefix('teacher')->name('teacher.')->group(function () {
    // Quizzes
    Route::resource('quizzes', QuizController::class);

    // Quiz Questions (nested)
    Route::prefix('quizzes/{quiz}')->group(function () {
        Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::get('questions/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
        Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

        // Quiz results
        Route::get('results', [QuizController::class, 'results'])->name('quizzes.results');
   });

    // Announcements
    Route::resource('announcements', AnnouncementController::class)->except(['show']);

    // Assignments
    Route::get('/assignments/create/{classroom_id}', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/edit/{assignment}', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/update/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/delete/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/assignments/download/{assignment}', [AssignmentController::class, 'download'])->name('assignments.download');
});

// Student routes
Route::middleware(['auth', IsStudent::class])->prefix('student')->name('student.')->group(function () {
    // Materials
    //Route::get('materials', [MaterialController::class, 'studentIndex'])->name('materials.index');
    //Route::get('materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');

    // Quizzes
    //Route::get('quizzes', [QuizController::class, 'studentIndex'])->name('quizzes.index');
    Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // Announcements
    Route::get('announcements', [AnnouncementController::class, 'studentIndex'])->name('announcements.index');

    // Assignments
    Route::get('/assignments/download/{assignment}', [AssignmentController::class, 'download'])->name('assignments.download');
    Route::post('/assignments/submit/{assignment}', [AssignmentController::class, 'submit'])->name('assignments.submit');
});

Route::middleware(['auth', IsStudent::class])->group(function () {
    Route::get('/assignments/download/{assignment}', [AssignmentController::class, 'download'])->name('student.assignments.download');
    Route::post('/assignments/submit/{assignment}', [AssignmentController::class, 'submit'])->name('student.assignments.submit');
    Route::delete('/assignments/{assignment}/submission', [AssignmentController::class, 'deleteSubmission'])->name('student.assignments.deleteSubmission');
});

// Classes / Teams-style pages
Route::middleware(['auth'])->prefix('classes')->group(function () {
    Route::get('/', [ClassPageController::class, 'index'])->name('classes.index');
    Route::get('/{class}/materials', [ClassPageController::class, 'materials'])->name('classes.materials');
    Route::get('/{class}/assignment', [ClassPageController::class, 'assignment'])->name('classes.assignment');
    Route::get('/{class}/quiz', [ClassPageController::class, 'quiz'])->name('classes.quiz');
    Route::get('/{class}/channel', [ChannelController::class, 'show'])->name('channel.show');

    // Posts & Comments
    Route::post('/{class}/post', [ClassPageController::class, 'storePost'])->name('channel.post');
    Route::post('/post/{post}/comment', [ClassPageController::class, 'storeComment'])->name('channel.comment');

    Route::put('/channel/post/{post}', [ClassPageController::class, 'update'])->name('channel.post.update');
    Route::delete('/channel/post/{post}', [ClassPageController::class, 'destroy'])->name('channel.post.delete');
    Route::put('/channel/comment/{comment}', [ClassPageController::class, 'updateComment'])->name('channel.comment.update');
    Route::delete('/channel/comment/{comment}', [ClassPageController::class, 'destroyComment'])->name('channel.comment.delete');

});

// Offline quiz submit
Route::post('offline-quizzes/{quiz}', [QuizController::class, 'offlineSubmit'])->name('offline.quizzes.submit');

// Legacy classroom quiz route aliases
Route::get('classrooms/{classroom}/quizzes', [QuizController::class, 'index'])->name('classes.quizzes');
Route::get('classrooms/{classroom}/quizzes', [QuizController::class, 'index'])->name('teacher.quizzes.index');

// List quizzes for a class (used in ClassPageController)
Route::middleware(['auth'])->prefix('classes')->group(function () {
    Route::get('/{class}/quizzes', [ClassPageController::class, 'quizzes'])
         ->name('classes.quizzes');
});

/*Route::get('/teacher/{classroomId}/quizzes',
    [QuizController::class, 'index']
)->name('teacher.quizzes.index');

Route::get('/classes/{classroomId}/quizzes',
    [QuizController::class, 'index']
)->name('classes.quizzes');


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
});*/

Route::post('offline-quizzes/{quiz}', [QuizController::class, 'offlineSubmit'])
    ->name('offline.quizzes.submit');

    Route::get('/teacher/quizzes/{quiz}/questions', [QuestionController::class, 'index'])
    ->name('teacher.questions.index');

// Student quiz list (attempt list)
Route::get('/student/quizzes/{quiz}', [QuizController::class, 'studentQuiz'])
    ->name('student.quizzes.single');

// Student: start quiz / show questions
Route::get('/student/quizzes/{quiz}/attempt', [QuizController::class, 'showStudent'])
    ->name('student.quizzes.show');

// Submit quiz answers
Route::post('/student/quizzes/{quiz}/submit', [QuizController::class, 'submit'])
    ->name('student.quizzes.submit');

// Review attempt
Route::get('/student/quizzes/{quiz}/review/{resultId}', [QuizController::class, 'review'])
    ->name('student.quizzes.review');

// Review a previous attempt
Route::get('/student/quizzes/{quiz}/{attempt}', [QuizController::class, 'review'])
    ->name('student.quizzes.review');

Route::middleware(['auth'])->group(function () {
    Route::get('teacher/quizzes/{quiz}/results', [QuizController::class, 'results'])
        ->name('teacher.quizzes.results');

    Route::get('teacher/quizzes/{quiz}/review/{result}', [QuizController::class, 'teacherReview'])
        ->name('teacher.quizzes.review');
});
// Teacher submission routes
Route::get('teacher/assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])
    ->name('teacher.assignments.submissions')
    ->middleware('auth'); // and your isTeacher middleware

Route::middleware(['auth'])
    ->prefix('classes')
    ->group(function () {

        Route::get('/{class}/materials', [MaterialController::class, 'classMaterials'])
            ->name('classes.materials');

});
Route::middleware(['auth'])->group(function () {

    // Download ONE file
    Route::get('/materials/file/{id}/download',
        [MaterialController::class, 'downloadFile'])
        ->name('materials.download.file');

    // Download ALL files (ZIP)
    Route::get('/materials/{id}/download-all',
        [MaterialController::class, 'downloadAll'])
        ->name('materials.download.all');
});

Route::get('/classes/{class}/materials', [ClassPageController::class, 'materials'])
     ->name('classes.materials');
Route::get('materials/file/{fileId}/view', [MaterialController::class, 'viewFile'])->name('teacher.materials.view');
// Edit material
Route::get('teacher/materials/{material}/edit', [MaterialController::class, 'edit'])->name('teacher.materials.edit');
Route::get('teacher/materials/{material}/edit', [MaterialController::class, 'edit'])->name('teacher.materials.edit');
Route::put('teacher/materials/{material}', [MaterialController::class, 'update'])->name('teacher.materials.update');

//Route::get('materials/{id}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
Route::put('materials/{id}', [MaterialController::class, 'update'])->name('materials.update');
Route::delete('materials/file/{id}', [MaterialController::class, 'destroyFile'])->name('teacher.materials.file.destroy');

Route::get('materials/{id}/redirect', [MaterialController::class, 'redirectLink'])->name('materials.redirect');
Route::get('materials/{id}/edit', [MaterialController::class, 'edit'])->name('teacher.materials.edit');

Route::middleware(['auth', IsTeacher::class])
    ->prefix('teacher')
    ->name('teacher.materials.') // ← This sets the prefix for all routes inside
    ->group(function () {

        Route::get('/', [MaterialController::class, 'teacherIndex'])->name('index');
        Route::get('create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');

        Route::get('{id}/download', [MaterialController::class, 'download'])->name('download');
        Route::get('{id}/view', [MaterialController::class, 'view'])->name('view');
        Route::get('{id}/download-all/{folder?}', [MaterialController::class, 'downloadAll'])->name('downloadAll');

        Route::delete('{id}', [MaterialController::class, 'destroy'])->name('destroy');
});

//student & teacher route
// View class materials
Route::get('/classes/{class}/materials', [MaterialController::class, 'classMaterials'])
    ->name('classes.materials');

// Download single file (students & teachers)
Route::get('/materials/download/{id}', [MaterialController::class, 'download'])
    ->name('materials.download');

// Download all files for a material (students & teachers)
Route::get('/materials/downloadAll/{material}/{folder?}', [MaterialController::class, 'downloadAll'])
    ->name('materials.downloadAll');

// Optional: Redirect YouTube or link
Route::get('/materials/link/{id}', [MaterialController::class, 'redirectLink'])
    ->name('materials.redirectLink');

Route::middleware(['auth'])->group(function () {
    Route::get('/classes/{id}', [ClassPageController::class, 'show'])->name('classes.show');
});

Route::get('/teacher/quizzes/{quiz}/results/{result}/grade', [QuizController::class, 'gradeShortAnswers'])
    ->name('teacher.quizzes.grade');

Route::post('/teacher/quizzes/{quiz}/results/{result}/grade', [QuizController::class, 'submitShortGrades'])
    ->name('teacher.quizzes.submit_grades');

Route::post('/teacher/quizzes/{quiz}/results/{result}/grade', [QuizController::class, 'submitShortGrades'])
    ->name('teacher.quizzes.submit_grades');

Route::post(
    '/teacher/quizzes/{quiz}/results/{result}/grade',
    [QuizController::class, 'submitGrades']
)->name('teacher.quizzes.submit_grades');

//PDPA
Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'privacyPolicy'])->name('privacy.policy');

Route::post('/consent', [\App\Http\Controllers\ConsentController::class, 'store'])
    ->name('consent.store')
    ->middleware('auth');

Route::middleware(['auth', '\App\Http\Middleware\IsAdmin'])->group(function () {
    Route::get('/admin/consent-report', [\App\Http\Controllers\AdminController::class, 'consentReport'])->name('admin.consent.report');
    Route::get('/audit-report', [AuditReportController::class, 'index'])->name('admin.audit-report');
});

//Route::get('/admin/audit-report/export', [AuditReportController::class, 'export'])->name('admin.audit-report.export');
Route::get('admin/audit-report/export-csv', [AuditReportController::class, 'exportCsv'])->name('admin.audit-report.export-csv');





