<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminFacultyController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\AdminSectionController;
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

    Route::post('/admin/examroom/update-schedule', [ExamScheduleController::class, 'updateSchedule'])->name('examroom.updateSchedule');


    Route::get('/admin/examroom', [ExamScheduleController::class, 'examRoomSchedule'])->name('admin.examroom.index');
    Route::post('/admin/examroom', [ExamScheduleController::class, 'storeExamRoom'])->name('admin.examroom.store');
    Route::put('/admin/examroom/{id}', [ExamScheduleController::class, 'updateExamRoom'])->name('admin.examroom.update');
    Route::delete('/admin/examroom/{id}', [ExamScheduleController::class, 'destroyExamRoom'])->name('admin.examroom.destroy');

    Route::prefix('/admin/examroom')->group(function () {
        Route::get('/', [ExamScheduleController::class, 'index'])->name('admin.examroom.index');
        Route::get('/create', [ExamScheduleController::class, 'create'])->name('admin.examroom.create');
        Route::post('/', [ExamScheduleController::class, 'store'])->name('admin.examroom.store');
        Route::get('/{examSchedule}/edit', [ExamScheduleController::class, 'edit'])->name('admin.examroom.edit');
        Route::put('/{examSchedule}', [ExamScheduleController::class, 'update'])->name('admin.examroom.update');
        Route::delete('/{examSchedule}', [ExamScheduleController::class, 'destroy'])->name('admin.examroom.destroy');
    });
    

    
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

    Route::get('/admin/schedules/{id}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::put('/admin/schedules/{id}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::get('/admin/schedules/{id}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');

    Route::get('/api/calendar-events', [CalendarEventController::class, 'fetchEvents'])->name('calendar-events.api');

    //Manage Section
    // Resource routes for sections
    Route::resource('/admin/sections', AdminSectionController::class)->names([
    'index' => 'admin.sections.index',
    'store' => 'admin.sections.store',
    'show' => 'admin.sections.show',
    'update' => 'admin.sections.update',
    'destroy' => 'admin.sections.destroy',
    ]);

    // Additional route if needed
    Route::get('/admin/section', [AdminSectionController::class, 'index'])->name('admin.section.index');
    

    // Manage Calendar (Admin only)
    Route::prefix('/admin/calendar')->group(function () {
        Route::get('/', [CalendarEventController::class, 'index'])->name('admin.calendar-events.index');
        Route::post('/', [CalendarEventController::class, 'store'])->name('admin.calendar-events.store');
        Route::get('/{calendarEvent}/edit', [CalendarEventController::class, 'edit'])->name('admin.calendar-events.edit');
        Route::put('/{calendarEvent}', [CalendarEventController::class, 'update'])->name('admin.calendar-events.update');
        Route::delete('/{calendarEvent}', [CalendarEventController::class, 'destroy'])->name('admin.calendar-events.destroy');
    });


// Program Chair Routes
Route::middleware(['auth:web', 'role:Program Chair'])->group(function () {
    Route::get('/program-chair/dashboard', [CalendarEventController::class, 'programChairDashboard'])->name('programchair.dashboard');
    Route::get('/program-chair/faculty/{id}/schedules', [ScheduleController::class, 'viewFacultySchedules'])->name('programchair.faculty.schedules');
    Route::resource('/program-chair/schedules', ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index' => 'programchair.schedules.index',
        'create' => 'programchair.schedules.create',
        'store' => 'programchair.schedules.store',
        'edit' => 'programchair.schedules.edit',
        'update' => 'programchair.schedules.update',
        'destroy' => 'programchair.schedules.destroy',
    ]);
    Route::get('/program-chair/calendar-events', [CalendarEventController::class, 'index'])->name('programchair.calendar-events.index');
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
Route::get('/admin/schedules/{id}', [ScheduleController::class, 'show']);

// Miscellaneous Routes
Route::get('/simple-page', fn() => view('simple'));
Route::get('/test-modal', fn() => view('test-modal'))->name('test-modal');
Route::get('/debug-role', fn() => Auth::check() ? 'Logged in as ' . Auth::user()->role : 'Not logged in')->middleware('auth:web');
Route::get('/test', fn() => 'Test route works!');

// Authentication Routes
Auth::routes();
