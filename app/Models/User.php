<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays / serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attribute casting map.
     *
     * Note: 'password' => 'hashed' requires Laravel >= 8.43 / 9+ depending on version.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* --------------------
     | Relationships
     | -------------------- */

    /**
     * Relasi ke tabel students (jika user student)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Relasi ke tabel lecturers (jika user lecturer)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    /**
     * Relasi ke tabel admins (jika user admin)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /* --------------------
     | Role helpers
     | -------------------- */

    /**
     * Cek apakah user memiliki role tertentu.
     * Case-insensitive.
     *
     * @param string|array $roles Single role or array of roles
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return strtolower($this->role ?? '') === strtolower($roles);
        }

        foreach ($roles as $r) {
            if (strtolower($this->role ?? '') === strtolower($r)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convenience: apakah student?
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Convenience: apakah lecturer?
     *
     * @return bool
     */
    public function isLecturer(): bool
    {
        return $this->hasRole('lecturer');
    }

    /**
     * Convenience: apakah admin?
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Mengembalikan profil spesifik berdasarkan role:
     * - student => $this->student
     * - lecturer => $this->lecturer
     * - admin => $this->admin
     *
     * Jika tidak ada role atau relasi belum dibuat, mengembalikan null.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function roleProfile()
    {
        return match ($this->role) {
            'student' => $this->student,
            'lecturer' => $this->lecturer,
            'admin' => $this->admin,
            default => null,
        };
    }
}
