<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionCycle extends Model
{
    protected $fillable = [
        'program_id',
        'intake_name',
        'starts_at',
        'deadline_at',
        'capacity'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'deadline_at' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getStatusAttribute()
    {
        $now = now();

        if ($now->lt($this->starts_at)) {
            return 'upcoming';
        }

        if ($now->between($this->starts_at, $this->deadline_at)) {
            return 'open';
        }

        return 'closed';
    }
}
