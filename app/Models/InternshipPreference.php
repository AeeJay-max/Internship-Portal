<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'preferred_department_id',
        'second_preferred_department_id',
        'area_of_internship',
        'specialisation',
        'preferred_start_date',
        'preferred_end_date',
        'required_duration',
        'institution_required_duration',
        'preferred_location',
        'flexible_department',
        'motivation_statement',
        'career_objectives',
        'internship_objectives',
        'skills_to_develop',
        'why_selected_department',
    ];

    protected $casts = [
        'preferred_start_date' => 'date',
        'preferred_end_date'   => 'date',
        'flexible_department'  => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function preferredDepartment()
    {
        return $this->belongsTo(Department::class, 'preferred_department_id');
    }

    public function secondPreferredDepartment()
    {
        return $this->belongsTo(Department::class, 'second_preferred_department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'preferred_department_id');
    }

    public function getPrimaryInterestAreaAttribute()
    {
        return $this->area_of_internship;
    }

    public function getSecondaryInterestAreaAttribute()
    {
        return $this->specialisation;
    }

    public function getPreferredDurationAttribute()
    {
        return $this->required_duration;
    }

    public function getPreferredStationAttribute()
    {
        return $this->preferred_location ?? 'Head Office (Harare)';
    }

    public function getSkillsAndCompetenciesAttribute()
    {
        return $this->skills_to_develop;
    }
}
