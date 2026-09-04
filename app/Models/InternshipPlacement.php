<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPlacement extends Model
{
    use HasFactory;

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
}
