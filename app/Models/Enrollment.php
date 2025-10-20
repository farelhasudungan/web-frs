<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id', 'course_id', 'status', 'grade', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // relasi many-to-one ke model student
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // relasi many-to-one ke model course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // method untuk menandai enrollment sebagai selesai
    public function markAsCompleted($grade = null): void
    {
        $this->update([
            'status' => 'completed',
            'grade' => $grade,
            'completed_at' => now()
        ]);
    }
}