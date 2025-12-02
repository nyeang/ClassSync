<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\TeacherDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Unified Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // AJAX checks
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
    Route::post('/check-student-id', [AuthController::class, 'checkStudentId'])->name('check-student-id');
});

// // Google OAuth Routes (outside guest middleware - accessible to all)
// Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
// Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Teacher Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'teacher'])->group(function () {
    // Dashboard - Using YOUR existing TeacherDashboardController
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // TODO: Add more teacher routes here
});

/*
|--------------------------------------------------------------------------
| Student Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    // Dashboard
    // Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // TODO: Add more student routes here
});
