<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacultyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin routes, only accessible by users with 'admin' role
Route::middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('/admin/faculty', AdminFacultyController::class)->names([
        'index' => 'admin.faculty.index',
        'store' => 'admin.faculty.store',
        'edit' => 'admin.faculty.edit',
        'update' => 'admin.faculty.update',
        'destroy' => 'admin.faculty.destroy',
    ]);
    Route::get('/admin/schedules/{id}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('/admin/schedules/{id}', [ScheduleController::class, 'update'])->name('admin.schedules.update');


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

    Route::resource('/admin/schedules', ScheduleController::class)->names([
        'index' => 'admin.schedules.index',
        'create' => 'admin.schedules.create',
        'store' => 'admin.schedules.store',
        'edit' => 'admin.schedules.edit',
        'update' => 'admin.schedules.update',
        'destroy' => 'admin.schedules.destroy',
    ]);
});

// Faculty routes, only accessible by users with 'faculty' role
Route::middleware(['auth:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [FacultyController::class, 'dashboard'])->name('faculty.dashboard');
    Route::get('/faculty/schedule', [FacultyController::class, 'viewSchedule'])->name('faculty.schedule');
    Route::get('/faculty/profile', [FacultyController::class, 'viewProfile'])->name('faculty.profile'); // Profile route
    Route::post('/faculty/profile/update', [FacultyController::class, 'updateProfile'])->name('faculty.profile.update'); // Profile update route
});


// Authentication routes
Auth::routes();
