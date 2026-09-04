<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantPersonalInfo extends Model
{

    protected $table = 'applicant_personal_info';

    protected $fillable = [
        'application_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'passport_number',
        'national_id',
        'country_of_birth',
        'place_of_birth',
        'resident_country',
        'citizenship',
        'nationality',
        'marital_status',
        'city',
        'province',
        'phone',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'armenian_language_proficiency',
        'other_languages',
        'ngo_membership',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
