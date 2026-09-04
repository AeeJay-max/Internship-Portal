<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleSlot extends Model
{
    protected $fillable = [
        'subject_id',
        'lecturer_id',
        'day',
        'time_start',
        'time_end',
        'building',
        'room',
        'type',
    ];

    // No datetime cast — keep as raw string so substr(time_start, 0, 5) works reliably
    // DB stores as TIME type: "09:30:00" — substr gives "09:30"

    const DAY_ORDER = [
        'Monday'    => 1,
        'Tuesday'   => 2,
        'Wednesday' => 3,
        'Thursday'  => 4,
        'Friday'    => 5,
        'Saturday'  => 6,
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    /**
     * Get time_start as HH:MM string regardless of DB format
     */
    public function getTimeStartShortAttribute(): string
    {
        return substr($this->time_start, 0, 5);
    }

    /**
     * Get time_end as HH:MM string regardless of DB format
     */
    public function getTimeEndShortAttribute(): string
    {
        return substr($this->time_end, 0, 5);
    }

    public function locationString(): string
    {
        $parts = array_filter([$this->room, $this->building]);
        return implode(', ', $parts) ?: '—';
    }
}
