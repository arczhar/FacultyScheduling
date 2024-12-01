<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacultyController;
use App\Http\Controllers\ExamScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Faculty Management
    Route::resource('/admin/faculty', AdminFacultyController::class)->names([
        'index' => 'admin.faculty.index',
        'store' => 'admin.faculty.store',
        'edit' => 'admin.faculty.edit',
        'update' => 'admin.faculty.update',
        'destroy' => 'admin.faculty.destroy',
    ]);
    Route::get('/admin/faculty/{id}', [AdminFacultyController::class, 'show'])->name('admin.faculty.show');

    Route::get('/admin/faculty/{id}/schedules', [ScheduleController::class, 'viewFacultySchedules'])->name('admin.faculty.schedules');

    // Subject Management
    Route::resource('/admin/subjects', SubjectController::class)->names([
        'index' => 'admin.subjects.index',
        'store' => 'admin.subjects.store',
        'edit' => 'admin.subjects.edit',
        'update' => 'admin.subjects.update',
        'destroy' => 'admin.subjects.destroy',
    ]);

    // Room Management
    Route::resource('/admin/rooms', RoomController::class)->names([
        'index' => 'admin.rooms.index',
        'store' => 'admin.rooms.store',
        'update' => 'admin.rooms.update',
        'destroy' => 'admin.rooms.destroy',
    ]);

    // Exam Room Management
    Route::prefix('/admin/examrm')->group(function () {
        Route::get('/examroom', [ExamScheduleController::class, 'index'])->name('admin.examrm.examroom');
        Route::get('/examroom/events', [ExamScheduleController::class, 'fetchEvents'])->name('admin.examrm.events');
        Route::post('/examroom/store', [ExamScheduleController::class, 'store'])->name('admin.examrm.store');
        Route::delete('/examroom/{id}', [ExamScheduleController::class, 'destroy'])->name('admin.examrm.destroy');
    });

    // Schedule Management
    Route::resource('/admin/schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index' => 'admin.schedules.index',
        'create' => 'admin.schedules.create',
        'store' => 'admin.schedules.store',
        'edit' => 'admin.schedules.edit',
        'update' => 'admin.schedules.update',
        'destroy' => 'admin.schedules.destroy',
    ]);
});

// Program Chair Routes
Route::middleware(['auth:web', 'role:Program Chair'])->group(function () {
    Route::get('/program-chair/dashboard', [AdminController::class, 'programChairDashboard'])->name('programchair.dashboard');
    Route::get('/program-chair/faculty/{id}/schedules', [ScheduleController::class, 'viewFacultySchedules'])->name('programchair.faculty.schedules');
    Route::resource('/program-chair/schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names([
        'index' => 'programchair.schedules.index',
        'create' => 'programchair.schedules.create',
        'store' => 'programchair.schedules.store',
        'edit' => 'programchair.schedules.edit',
        'update' => 'programchair.schedules.update',
    ]);
});

// Faculty Routes
Route::middleware(['auth:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [FacultyController::class, 'dashboard'])->name('faculty.dashboard');
    Route::get('/faculty/schedule', [FacultyController::class, 'viewSchedule'])->name('faculty.schedule');
    Route::get('/faculty/profile', [FacultyController::class, 'viewProfile'])->name('faculty.profile');
    Route::post('/faculty/profile/update', [FacultyController::class, 'updateProfile'])->name('faculty.profile.update');
    Route::get('/faculty/change-password', [FacultyController::class, 'showChangePasswordForm'])->name('faculty.change-password');
    Route::post('/faculty/change-password', [FacultyController::class, 'updatePassword'])->name('faculty.update-password');
});

// AJAX Routes
Route::get('/get-faculty-details/{facultyId}', [ScheduleController::class, 'getFacultyDetails'])->name('get-faculty-details');
Route::get('/get-subject-details/{id}', [SubjectController::class, 'getSubjectDetails'])->name('get-subject-details');
Route::post('/check-schedule-conflict', [ScheduleController::class, 'checkAndSaveSchedule'])->name('check-schedule-conflict');

// Miscellaneous Routes
Route::get('/simple-page', fn() => view('simple'));
Route::get('/test-modal', fn() => view('test-modal'))->name('test-modal');
Route::get('/debug-role', fn() => Auth::check() ? 'Logged in as ' . Auth::user()->role : 'Not logged in')->middleware('auth:web');
Route::get('/test', fn() => 'Test route works!');

// Authentication Routes
Auth::routes();
