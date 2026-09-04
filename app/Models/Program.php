<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'faculty',
        'degree_level',
        'description',
        'is_active',
        'is_featured',
        'sort_order',
        'image_path',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function admissionCycles()
    {
        return $this->hasMany(AdmissionCycle::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function subjects()
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
    public function isBachelor()
    {
        return $this->degree_level === 'bachelor';
    }

    public function isMaster()
    {
        return $this->degree_level === 'master';
    }

    public function isPhd()
    {
        return $this->degree_level === 'phd';
    }

}
