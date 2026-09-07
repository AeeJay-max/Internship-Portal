<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipPlacement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'department_id',
        'division_unit',
        'placement_location',
        'supervisor_name',
        'supervisor_email',
        'supervisor_phone',
        'start_date',
        'end_date',
        'duration',
        'placement_notes',
        'reporting_instructions',
        'assigned_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function getStationAttribute()
    {
        return $this->placement_location ?? 'Head Office (Harare)';
    }

    public function getPlacementCodeAttribute()
    {
        return sprintf('MoSRAC-PLC-%06d', $this->id);
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->end_date) return 999;
        return (int) ceil(now()->diffInDays($this->end_date, false));
    }

    public function isExpiringSoon(): bool
    {
        if (!$this->end_date) return false;
        $days = $this->days_remaining;
        return $days >= 0 && $days <= 30;
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) return false;
        return $this->days_remaining < 0;
    }
}
