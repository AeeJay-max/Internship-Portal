<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipOpportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'title',
        'description',
        'requirements',
        'positions_count',
        'opening_date',
        'closing_date',
        'duration_months',
        'start_date',
        'status',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'closing_date' => 'date',
        'start_date'   => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'opportunity_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' &&
            ($this->closing_date === null || $this->closing_date->isFuture() || $this->closing_date->isToday());
    }
}
