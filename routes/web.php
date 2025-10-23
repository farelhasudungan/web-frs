<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; // gunakan namespace root App\Http\Controllers

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// =====================
// Guest Routes
// =====================
Route::middleware(['guest'])->group(function () {

    // Welcome page
    Route::get('/', function () {
        return view('welcome');
    });

    // Authentication routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// =====================
// Logout Route (Global)
// =====================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =====================
// Authenticated Routes
// =====================
Route::middleware(['auth'])->group(function () {

    // Dashboard (universal)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // =====================
    // Profile Management
    // =====================
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::post('/profile/setup', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // =====================
    // Course Management (Lecturer + Admin)
    // =====================
    Route::middleware(['role:lecturer,admin'])->group(function () {
        Route::resource('courses', CourseController::class);
    });

    // =====================
    // Admin Section (Manage Users)
    // =====================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // =====================
    // Student Enrollment
    // =====================
    Route::middleware(['role:student'])->prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/available', [EnrollmentController::class, 'availableCourses'])->name('available');
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('my-courses');
        Route::post('/enroll/{course}', [EnrollmentController::class, 'enroll'])->name('enroll');
        Route::post('/drop/{course}', [EnrollmentController::class, 'drop'])->name('drop');
    });
});
