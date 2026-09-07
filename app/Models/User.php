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

    /* ===============================
       APPLICATION LIMIT & DEPT HELPERS
    =============================== */

    public function generalApplication()
    {
        return $this->applications()
            ->whereNull('opportunity_id')
            ->where('status', '!=', Application::STATUS_WITHDRAWN)
            ->first();
    }

    public function hasGeneralApplication(): bool
    {
        return $this->applications()
            ->whereNull('opportunity_id')
            ->where('status', '!=', Application::STATUS_WITHDRAWN)
            ->exists();
    }

    public function opportunityApplication()
    {
        return $this->applications()
            ->whereNotNull('opportunity_id')
            ->where('status', '!=', Application::STATUS_WITHDRAWN)
            ->first();
    }

    public function hasOpportunityApplication(): bool
    {
        return $this->applications()
            ->whereNotNull('opportunity_id')
            ->where('status', '!=', Application::STATUS_WITHDRAWN)
            ->exists();
    }

    public function hasReachedMaxApplications(): bool
    {
        return $this->hasGeneralApplication() && $this->hasOpportunityApplication();
    }

    public function getAppliedDepartment()
    {
        foreach ($this->applications()->where('status', '!=', Application::STATUS_WITHDRAWN)->get() as $app) {
            if ($app->opportunity_id && $app->opportunity && $app->opportunity->department) {
                return $app->opportunity->department;
            }
            if ($app->preference && $app->preference->preferredDepartment) {
                return $app->preference->preferredDepartment;
            }
        }
        return null;
    }

    public function getAppliedDepartmentId(): ?int
    {
        return $this->getAppliedDepartment()?->id;
    }

    public function canApplyToDepartment(?int $departmentId): bool
    {
        $appliedDeptId = $this->getAppliedDepartmentId();
        if (!$appliedDeptId || !$departmentId) {
            return true;
        }
        return (int) $appliedDeptId === (int) $departmentId;
    }
}
