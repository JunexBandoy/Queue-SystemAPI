<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'section_id',
        'email',
        'password',
        // 'role'  // keep excluded from fillable for security
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Hash password when set, but avoid double-hashing existing hashes.
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

    // Optional helpers (safe even if role may be null)
    public function isAdmin(): bool { return ($this->role ?? null) === 'admin'; }
    public function isManager(): bool { return ($this->role ?? null) === 'manager'; }
}