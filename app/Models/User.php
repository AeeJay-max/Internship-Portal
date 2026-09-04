<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'nationality',
        'is_active',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /* ===============================
       ROLE HELPERS
    =============================== */

    public function sendEmailVerificationNotification(): void
    {
        try {
            $this->notify(new CustomVerifyEmail);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending email verification notification: ' . $e->getMessage());
        }
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'placement_officer']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPlacementOfficer(): bool
    {
        return in_array($this->role, ['placement_officer', 'super_admin']);
    }

    public function isApplicant(): bool
    {
        return in_array($this->role, ['applicant', 'student']);
    }

    /* ===============================
       RELATIONSHIPS
    =============================== */

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
