<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTransferInfo extends Model
{

    protected $table = 'applicant_transfer_info';
    protected $fillable = [
        'application_id',
        'previous_institution_name',
        'previous_institution_country',
        'previous_degree_program',
        'enrollment_date',
        'last_attendance_date',
        'completed_semesters',
        'earned_credits',
        'disciplinary_actions',
        'disciplinary_details',
        'reason_for_transfer',
    ];

    protected $casts = [
        'disciplinary_actions' => 'boolean',
        'enrollment_date' => 'date',
        'last_attendance_date' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}

