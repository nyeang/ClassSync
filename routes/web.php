<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;

// ========== HOME REDIRECT ==========
Route::get('/', function () {
    return auth()->check()
        ? redirect(auth()->user()->getDashboardRoute() ?? 'login')
        : redirect('login');
});

// ========== GUEST ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

// ========== AUTHENTICATED ROUTES ==========
Route::middleware('auth')->group(function () {
    // Logout (available to all authenticated users)
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard (main page)
    Route::get('/', [UserController::class, 'index'])->name('dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');

    // User management (form submissions)
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Class management (form submissions)
    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
    Route::post('classes', [ClassController::class, 'store'])->name('classes.store');

    Route::get('all-users', [UserController::class, 'getAllUser'])->name('all-users');

    // ========== API ENDPOINTS (AJAX) ==========
    Route::prefix('api')->name('api.')->group(function () {
        // Dashboard Stats
        Route::get('stats', [UserController::class, 'getStats'])->name('stats');

        // Users API
        Route::get('users', [UserController::class, 'getUsers'])->name('users');
        Route::get('recent-users', [UserController::class, 'getRecentUsers'])->name('recent-users');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Password Reset Requests API
        Route::get('password-requests', [UserController::class, 'getPasswordRequests'])->name('password-requests');
        Route::post('password-requests/{id}/process', [UserController::class, 'processPasswordRequest'])->name('password-requests.process');

        // Classes API
        Route::get('classes', [ClassController::class, 'getClasses'])->name('classes');
        Route::post('classes', [ClassController::class, 'store'])->name('classes.store');
        Route::put('classes/{id}', [ClassController::class, 'update'])->name('classes.update');
        Route::post('classes/{id}/toggle-archive', [ClassController::class, 'toggleArchive'])->name('classes.toggle-archive');
        Route::post('classes/{id}/reset-code', [ClassController::class, 'resetClassCode'])->name('classes.reset-code');
        Route::delete('classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

        //User
        
    });
});