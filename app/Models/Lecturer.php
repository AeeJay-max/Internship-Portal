<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    protected $fillable = [
        'name',
        'title',
        'email',
        'faculty',
    ];

    public function scheduleSlots(): HasMany
    {
        return $this->hasMany(ScheduleSlot::class);
    }

    public function fullTitle(): string
    {
        return $this->title . ' ' . $this->name;
    }
}
