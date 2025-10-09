<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'status', 'grade', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function markAsCompleted($grade = null): void
    {
        $this->update([
            'status' => 'completed',
            'grade' => $grade,
            'completed_at' => now()
        ]);
    }
}