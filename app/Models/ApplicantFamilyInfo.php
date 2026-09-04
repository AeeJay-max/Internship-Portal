<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantFamilyInfo extends Model
{
    protected $table = 'applicant_family_info';

    protected $fillable = [
        'application_id',
        'father_full_name',
        'father_occupation',
        'mother_full_name',
        'mother_occupation',
        'siblings',
    ];

    protected $casts = [
        'siblings' => 'array',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
