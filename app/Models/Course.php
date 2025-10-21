<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'credits', 
        'max_students', 'enrolled_count', 'status'
    ];

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class, 'course_lecturer');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot('status', 'grade', 'completed_at')
                    ->withTimestamps();
    }


    // relasi many-to-many ke model course sendiri (self-referencing) melalui tabel pivot course_dependencies
    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_dependencies', 'course_id', 'prerequisite_id');
    }

    // relasi kebalikan dari prerequisites (course lain yang menjadikan course ini sebagai prasyarat)
    // public function dependentCourses(): BelongsToMany
    // {
    //     return $this->belongsToMany(Course::class, 'course_dependencies', 'prerequisite_id', 'course_id');
    // }

    // relasi one to many (satu course bisa memiliki banyak enrollment)
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // relasi many-to-many ke mahasiswa melalui tabel pivot enrollments, hanya yang statusnya enrolled
    public function enrolledStudents(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->wherePivot('status', 'enrolled');
    }

    // cek ketersediaan
    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->enrolled_count < $this->max_students;
    }

    // cek ruang
    public function hasSpace(): bool
    {
        return $this->enrolled_count < $this->max_students;
    }

    // tambahkan satu pada enrolled_count
    public function incrementEnrollment(): void
    {
        $this->increment('enrolled_count');
    }

    // kurangi satu pada enrolled_count
    public function decrementEnrollment(): void
    {
        $this->decrement('enrolled_count');
    }
}