<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacultyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['auth:web', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Faculty Management
    Route::resource('faculty', AdminFacultyController::class)->except(['create', 'show']);
    Route::get('faculty/{id}/edit', [AdminFacultyController::class, 'edit'])->name('faculty.edit');
    Route::get('faculty/{id}', [AdminFacultyController::class, 'show'])->name('faculty.show');
    Route::put('faculty/{id}', [AdminFacultyController::class, 'update']);

    // Subject Management
    Route::resource('subjects', SubjectController::class)->except(['create', 'show']);

    // Room Management
    Route::resource('rooms', RoomController::class)->except(['create', 'show']);

    // Schedule Management
    Route::resource('schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

// Program Chair Routes
Route::middleware(['auth:web', 'role:Program Chair'])->prefix('program-chair')->name('programchair.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'programChairDashboard'])->name('dashboard');

    // Schedule Management (limited for Program Chair)
    Route::resource('schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::delete('schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

// Faculty Routes
Route::middleware(['auth:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedule', [FacultyController::class, 'viewSchedule'])->name('schedule');
    Route::get('/profile', [FacultyController::class, 'viewProfile'])->name('profile');
    Route::post('/profile/update', [FacultyController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [FacultyController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [FacultyController::class, 'updatePassword'])->name('update-password');
});

// AJAX Routes
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::get('get-faculty-details/{facultyId}', [ScheduleController::class, 'getFacultyDetails'])->name('get-faculty-details');
    Route::get('get-subjects', [ScheduleController::class, 'getSubjects'])->name('get-subjects');
    Route::get('get-subject-details/{id}', [SubjectController::class, 'getSubjectDetails'])->name('get-subject-details');
    Route::post('check-schedule-conflict', [ScheduleController::class, 'checkAndSaveSchedule'])->name('check-schedule-conflict');
});

// Miscellaneous Testing Routes
Route::view('/simple-page', 'simple')->name('simple-page');
Route::view('/test-modal', 'test-modal')->name('test-modal');

// Debug Route
Route::get('/debug-role', function () {
    return Auth::check() ? 'Logged in as ' . Auth::user()->role : 'Not logged in';
})->middleware('auth:web');

// Authentication Routes
Auth::routes();
