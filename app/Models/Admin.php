<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $admin_name
 * @property string|null $email
 * @property string|null $department
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Hapus / sesuaikan jika Anda menggunakan konvensi default 'admins')
     *
     * @var string|null
     */
    // protected $table = 'admins';

    /**
     * Mass assignable attributes.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'admin_name',
        'email',
        'department',
        'address',
    ];

    /**
     * Attribute casts.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Admin belongs to a User (optional).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Display name accessor.
     * - returns admin_name if set, otherwise falls back to related user's name.
     *
     * Usage: $admin->display_name
     *
     * @return string|null
     */
    public function getDisplayNameAttribute(): ?string
    {
        if (!empty($this->admin_name)) {
            return $this->admin_name;
        }

        return $this->user?->name;
    }
}
