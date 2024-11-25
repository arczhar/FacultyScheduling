<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacultyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin routes, only accessible by users with the 'Admin' role
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin can manage everything
    Route::resource('/admin/faculty', AdminFacultyController::class)->names([
        'index' => 'admin.faculty.index',
        'store' => 'admin.faculty.store',
        'edit' => 'admin.faculty.edit',
        'update' => 'admin.faculty.update',
        'destroy' => 'admin.faculty.destroy',
    ]);

    Route::resource('/admin/subjects', SubjectController::class)->names([
        'index' => 'admin.subjects.index',
        'store' => 'admin.subjects.store',
        'edit' => 'admin.subjects.edit',
        'update' => 'admin.subjects.update',
        'destroy' => 'admin.subjects.destroy',
    ]);

    Route::resource('/admin/rooms', RoomController::class)->names([
        'index' => 'admin.rooms.index',
        'store' => 'admin.rooms.store',
        'update' => 'admin.rooms.update',
        'destroy' => 'admin.rooms.destroy',
    ]);

    Route::resource('/admin/schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index' => 'admin.schedules.index',
        'create' => 'admin.schedules.create',
        'store' => 'admin.schedules.store',
        'edit' => 'admin.schedules.edit',
        'update' => 'admin.schedules.update',
        'destroy' => 'admin.schedules.destroy',
    ]);

    Route::delete('/admin/schedules/{id}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');

});


// Program Chair routes, only accessible by users with the 'Program Chair' role
Route::middleware(['auth:web', 'role:program Chair'])->group(function () {
    Route::get('/program-chair/dashboard', [AdminController::class, 'programChairDashboard'])->name('programchair.dashboard');

    // Program Chair can only manage schedules
    Route::resource('/program-chair/schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names([
        'index' => 'programchair.schedules.index',
        'create' => 'programchair.schedules.create',
        'store' => 'programchair.schedules.store',
        'edit' => 'programchair.schedules.edit',
        'update' => 'programchair.schedules.update',
    ]);

    Route::delete('/program-chair/schedules/{id}', [ScheduleController::class, 'destroy'])->name('programchair.schedules.destroy');
});



// AJAX routes for fetching details and conflict checking
Route::get('/get-faculty-details/{facultyId}', [ScheduleController::class, 'getFacultyDetails'])->name('get-faculty-details');
Route::get('/get-subjects', [ScheduleController::class, 'getSubjects'])->name('get-subjects');
Route::get('/get-subject-details/{id}', [SubjectController::class, 'getSubjectDetails'])->name('get-subject-details');
Route::post('/check-schedule-conflict', [ScheduleController::class, 'checkAndSaveSchedule'])->name('check-schedule-conflict');
Route::post('/admin/schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');

// Faculty routes, only accessible by users with 'faculty' role
Route::middleware(['auth:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [FacultyController::class, 'dashboard'])->name('faculty.dashboard');
    Route::get('/faculty/schedule', [FacultyController::class, 'viewSchedule'])->name('faculty.schedule');
    Route::get('/faculty/profile', [FacultyController::class, 'viewProfile'])->name('faculty.profile'); // Profile route
    Route::post('/faculty/profile/update', [FacultyController::class, 'updateProfile'])->name('faculty.profile.update'); // Profile update route
});

Route::get('/faculty/change-password', [FacultyController::class, 'showChangePasswordForm'])
    ->name('faculty.change-password')
    ->middleware(['auth:faculty']);

Route::post('/faculty/change-password', [FacultyController::class, 'updatePassword'])
    ->name('faculty.update-password')
    ->middleware(['auth:faculty']);

// Miscellaneous routes for testing or additional functionality
Route::get('/simple-page', function () {
    return view('simple');
});

Route::get('/test-modal', function () {
    return view('test-modal');
})->name('test-modal');


Route::get('/debug-role', function () {
    return Auth::check() ? 'Logged in as ' . Auth::user()->role : 'Not logged in';
})->middleware('auth:web');


// Authentication routes
Auth::routes();
