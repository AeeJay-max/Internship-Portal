<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'contact_email',
        'contact_phone',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function opportunities()
    {
        return $this->hasMany(InternshipOpportunity::class);
    }

    public function preferences()
    {
        return $this->hasMany(InternshipPreference::class, 'preferred_department_id');
    }

    public function placements()
    {
        return $this->hasMany(InternshipPlacement::class);
    }
}
