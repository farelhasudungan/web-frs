<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('status', 'grade', 'completed_at')
                    ->withTimestamps();
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->courses()->wherePivot('status', 'enrolled');
    }

    public function completedCourses(): BelongsToMany
    {
        return $this->courses()->wherePivot('status', 'completed');
    }

    public function hasCompletedCourse($courseId): bool
    {
        return $this->completedCourses()->where('courses.id', $courseId)->exists();
    }

    public function hasCompletedPrerequisites(Course $course): bool
    {
        $prerequisiteIds = $course->prerequisites->pluck('id');
        
        if ($prerequisiteIds->isEmpty()) {
            return true;
        }

        $completedIds = $this->completedCourses()->pluck('courses.id');
        
        return $prerequisiteIds->every(function ($id) use ($completedIds) {
            return $completedIds->contains($id);
        });
    }

    public function canEnrollInCourse(Course $course): array
    {
        $errors = [];

        // Check if already enrolled
        if ($this->enrolledCourses()->where('courses.id', $course->id)->exists()) {
            $errors[] = 'Already enrolled in this course';
        }

        // Check if already completed
        if ($this->hasCompletedCourse($course->id)) {
            $errors[] = 'Already completed this course';
        }

        // Check prerequisites
        if (!$this->hasCompletedPrerequisites($course)) {
            $errors[] = 'Prerequisites not met';
        }

        // Check course availability
        if (!$course->isAvailable()) {
            $errors[] = 'Course is full or inactive';
        }

        return $errors;
    }
}
