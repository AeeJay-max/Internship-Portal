<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'code',
        'name',
        'credits',
        'hours_per_week',
        'subject_type',
        'year_of_study',
        'description',
        'semester_id',
        'program_id',
    ];

    protected $casts = [
        'credits'        => 'integer',
        'hours_per_week' => 'integer',
        'year_of_study'  => 'integer',
    ];

    const TYPES = [
        'major_core'     => 'Major Core',
        'major_elective' => 'Major Elective',
        'general'        => 'General Education',
    ];

    const TYPE_COLORS = [
        'major_core'     => ['#eff6ff', '#1d4ed8'],
        'major_elective' => ['#f5f3ff', '#7c3aed'],
        'general'        => ['#f0fdf4', '#15803d'],
    ];

    public function semester(): BelongsTo { return $this->belongsTo(Semester::class); }
    public function program(): BelongsTo  { return $this->belongsTo(Program::class); }
    public function scheduleSlots(): HasMany { return $this->hasMany(ScheduleSlot::class); }
    public function enrollments(): HasMany   { return $this->hasMany(StudentSubjectEnrollment::class); }

    public function remainingSlots(): int
    {
        return max(0, $this->hours_per_week - $this->scheduleSlots()->count());
    }

    public function isFullyScheduled(): bool
    {
        return $this->remainingSlots() === 0;
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->subject_type] ?? $this->subject_type;
    }

    public function typeColors(): array
    {
        return self::TYPE_COLORS[$this->subject_type] ?? ['#f3f4f6', '#6b7280'];
    }

    public function hoursLabel(): string
    {
        return $this->hours_per_week . ' ' . ($this->hours_per_week === 1 ? 'slot' : 'slots') . '/week';
    }
}
