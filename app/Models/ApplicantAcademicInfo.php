<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantAcademicInfo extends Model
{

    protected $table = 'applicant_academic_info';

    protected $fillable = [
        'application_id',
        'school_name',
        'institution_type',
        'program_of_study',
        'field_of_study',
        'current_year_level',
        'academic_qualification',
        'expected_graduation_date',
        'institution_city',
        'institution_country',
        'graduation_year',
        'graduation_date',
        'gpa',
        'bachelor_gpa',
        'previous_university',
        'degree_obtained',
        'specialization',
        'language_of_instruction',
        'additional_certifications',
        'research_topic',
        'master_university',
        'master_degree_obtained',
        'master_gpa',
    ];

    protected $casts = [
        'gpa'                       => 'decimal:2',
        'bachelor_gpa'              => 'decimal:2',
        'master_gpa'                => 'decimal:2',
        'graduation_date'           => 'date',
        'expected_graduation_date'  => 'date',
        'additional_certifications' => 'array',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getQualificationLevelAttribute()
    {
        return $this->academic_qualification ?? $this->degree_obtained;
    }

    public function getCurrentYearAttribute()
    {
        return $this->current_year_level;
    }
}
