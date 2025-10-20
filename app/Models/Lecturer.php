<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    protected $fillable = [
        'user_id',
        'lecturer_name',
        'department',
        'laboratorium',
        'phone',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
