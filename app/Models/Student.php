<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'student_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi one-to-many ke model enrollment
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // relasi many-to-many ke model course lewat tabel pivot enrollments
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('status', 'grade', 'completed_at') // return dengan tabel pivot
                    ->withTimestamps(); // return dengan timestamp 
    }

    // ngambil courses yang status-nya enrolled di tabel pivot
    public function enrolledCourses(): BelongsToMany
    {
        return $this->courses()->wherePivot('status', 'enrolled');
    }

    // ngambil courses yang status-nya completed di tabel pivot
    public function completedCourses(): BelongsToMany
    {
        return $this->courses()->wherePivot('status', 'completed');
    }

    // ngecek apakah user sudah menyelesaikan course (based on id)
    public function hasCompletedCourse($courseId): bool
    {
        return $this->completedCourses()->where('courses.id', $courseId)->exists();
    }

    // ngecek persyaratan
    public function hasCompletedPrerequisites(Course $course): bool
    {
        $prerequisiteIds = $course->prerequisites->pluck('id'); // ambil semua prerequisites yg ada di course
        
        if ($prerequisiteIds->isEmpty()) { // apakah ada prasyarat, jika tidak, return true aja
            return true;
        }

        $completedIds = $this->completedCourses()->pluck('courses.id'); // ambil semua course yg udh diselesaiin
        
        return $prerequisiteIds->every(function ($id) use ($completedIds) {
            return $completedIds->contains($id); // cek apakah prasyarat sudah diambil semua
        });
    }

    public function canEnrollInCourse(Course $course): array
    {
        $errors = [];

        // cek apakah sudah diambil
        if ($this->enrolledCourses()->where('courses.id', $course->id)->exists()) {
            $errors[] = 'Already enrolled in this course';
        }

        // cek apakah sudah diselesaikan
        if ($this->hasCompletedCourse($course->id)) {
            $errors[] = 'Already completed this course';
        }

        // cek prasyarat
        if (!$this->hasCompletedPrerequisites($course)) {
            $errors[] = 'Prerequisites not met';
        }

        // cek kapasitas
        if (!$course->isAvailable()) {
            $errors[] = 'Course is full or inactive';
        }

        return $errors;
    }
}
