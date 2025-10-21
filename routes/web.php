<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    // Authentication routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::post('/profile/setup', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Course management (lecturers and admins only)
    Route::middleware(['role:lecturer,admin'])->group(function () {
        Route::resource('courses', CourseController::class);
    });

    // Enrollment routes (students)
    Route::middleware(['role:student'])->prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/available', [EnrollmentController::class, 'availableCourses'])->name('available');
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('my-courses');
        Route::post('/enroll/{course}', [EnrollmentController::class, 'enroll'])->name('enroll');
        Route::post('/drop/{course}', [EnrollmentController::class, 'drop'])->name('drop');
    });
});