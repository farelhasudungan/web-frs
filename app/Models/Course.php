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

    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_dependencies', 'course_id', 'prerequisite_id');
    }

    public function dependentCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_dependencies', 'prerequisite_id', 'course_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->wherePivot('status', 'enrolled');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->enrolled_count < $this->max_students;
    }

    public function hasSpace(): bool
    {
        return $this->enrolled_count < $this->max_students;
    }

    public function incrementEnrollment(): void
    {
        $this->increment('enrolled_count');
    }

    public function decrementEnrollment(): void
    {
        $this->decrement('enrolled_count');
    }
}