<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'lecturer_name',
        'department',
        'laboratorium',
        'phone',
        'address',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_lecturer');
    }


    public function students()
    {
        return Student::whereHas('enrollments.course.lecturers', function($query) {
            $query->where('lecturers.id', $this->id)
                ->where('enrollments.status', 'enrolled');
        });
    }
}
