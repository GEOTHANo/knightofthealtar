<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MassController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Protected Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Members
    Route::middleware('permission:members')->group(function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::patch('/members/{member}/archive', [MemberController::class, 'archive'])->name('members.archive');
    });

    // Mass
    Route::middleware('permission:mass')->group(function () {
        Route::get('/mass', [MassController::class, 'index'])->name('mass.index');
        Route::post('/mass/weekday', [MassController::class, 'storeWeekday'])->name('mass.weekday.store');
        Route::post('/mass/sunday', [MassController::class, 'storeSunday'])->name('mass.sunday.store');
        Route::put('/mass/weekday/{massWeekday}', [MassController::class, 'updateWeekday'])->name('mass.weekday.update');
        Route::put('/mass/sunday/{massSunday}', [MassController::class, 'updateSunday'])->name('mass.sunday.update');
        Route::patch('/mass/weekday/{massWeekday}/archive', [MassController::class, 'archiveWeekday'])->name('mass.weekday.archive');
        Route::patch('/mass/sunday/{massSunday}/archive', [MassController::class, 'archiveSunday'])->name('mass.sunday.archive');
    });

    // Attendance
    Route::middleware('permission:attendance')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/check', [AttendanceController::class, 'check'])->name('attendance.check');
        Route::post('/attendance/save', [AttendanceController::class, 'save'])->name('attendance.save');
    });

    // Schedules
    Route::middleware('permission:schedules')->group(function () {
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        
        Route::middleware('permission:schedules.create')->group(function () {
            Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
            Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
            Route::get('/schedules/check', [ScheduleController::class, 'check'])->name('schedules.check');
            Route::post('/schedules/submit-check', [ScheduleController::class, 'submitCheck'])->name('schedules.submitCheck');
        });

        Route::get('/schedules/{weekStart}', [ScheduleController::class, 'show'])->name('schedules.show');
    });

    // Notifications
    Route::middleware('permission:notifications')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    });

    // Settings
    Route::middleware('permission:settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
