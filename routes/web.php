<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Course management (admin)
    Route::resource('courses', CourseController::class);

    // Enrollment routes (students)
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/available', [EnrollmentController::class, 'availableCourses'])->name('available');
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('my-courses');
        Route::post('/enroll/{course}', [EnrollmentController::class, 'enroll'])->name('enroll');
        Route::post('/drop/{course}', [EnrollmentController::class, 'drop'])->name('drop');
    });
});