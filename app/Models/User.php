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
        'role',
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

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }
    
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
    
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
    
    public function isLecturer(): bool
    {
        return $this->role === 'lecturer';
    }
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function roleProfile()
    {
        return match($this->role) {
            'student' => $this->student,
            'lecturer' => $this->lecturer,
            'admin' => $this->admin,
            default => null
        };
    }
}
