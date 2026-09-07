<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    const STATUS_DRAFT                 = 'draft';
    const STATUS_SUBMITTED             = 'submitted';
    const STATUS_UNDER_REVIEW          = 'under_review';
    const STATUS_DOCUMENTS_REQUESTED   = 'documents_requested';
    const STATUS_SHORTLISTED           = 'shortlisted';
    const STATUS_INTERVIEW_REQUIRED    = 'interview_required';
    const STATUS_APPROVED              = 'approved';
    const STATUS_PLACEMENT_PENDING     = 'placement_pending';
    const STATUS_PLACED                = 'placed';
    const STATUS_REJECTED              = 'rejected';
    const STATUS_WITHDRAWN             = 'withdrawn';

    protected $fillable = [
        'user_id',
        'reference_number',
        'opportunity_id',
        'status',
        'current_step',
        'completion_percentage',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'interview_date',
        'interview_time',
        'interview_location',
        'flagged_document_ids',
        'needs_reupload_review',
        'flagged_cert_names',
    ];

    protected $casts = [
        'submitted_at'          => 'datetime',
        'reviewed_at'           => 'datetime',
        'interview_date'        => 'date',
        'flagged_document_ids'  => 'array',
        'needs_reupload_review' => 'boolean',
        'flagged_cert_names'    => 'array',
    ];

    public static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $nextId = (self::max('id') ?? 0) + 1;
        return sprintf('MoSRAC-INT-%s-%06d', $year, $nextId);
    }

    /* ===============================
       STATUS HELPERS
    =============================== */

    public function isDraft(): bool { return $this->status === self::STATUS_DRAFT; }
    public function isSubmitted(): bool { return $this->status === self::STATUS_SUBMITTED; }
    public function isUnderReview(): bool { return $this->status === self::STATUS_UNDER_REVIEW; }
    public function isDocumentsRequested(): bool { return $this->status === self::STATUS_DOCUMENTS_REQUESTED; }
    public function isShortlisted(): bool { return $this->status === self::STATUS_SHORTLISTED; }
    public function isInterviewRequired(): bool { return $this->status === self::STATUS_INTERVIEW_REQUIRED; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isPlacementPending(): bool { return $this->status === self::STATUS_PLACEMENT_PENDING; }
    public function isPlaced(): bool { return $this->status === self::STATUS_PLACED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
    public function isWithdrawn(): bool { return $this->status === self::STATUS_WITHDRAWN; }

    public function statusBadge(): array
    {
        return match ($this->status) {
            self::STATUS_DRAFT                => ['label' => 'Draft',                'class' => 'bg-yellow-100 text-yellow-800'],
            self::STATUS_SUBMITTED            => ['label' => 'Submitted',            'class' => 'bg-blue-100 text-blue-800'],
            self::STATUS_UNDER_REVIEW         => ['label' => 'Under Review',         'class' => 'bg-purple-100 text-purple-800'],
            self::STATUS_DOCUMENTS_REQUESTED  => ['label' => 'Documents Required',   'class' => 'bg-orange-100 text-orange-800'],
            self::STATUS_SHORTLISTED          => ['label' => 'Shortlisted',          'class' => 'bg-teal-100 text-teal-800'],
            self::STATUS_INTERVIEW_REQUIRED   => ['label' => 'Interview Required',   'class' => 'bg-indigo-100 text-indigo-800'],
            self::STATUS_APPROVED             => ['label' => 'Approved',             'class' => 'bg-emerald-100 text-emerald-800'],
            self::STATUS_PLACEMENT_PENDING    => ['label' => 'Placement Pending',    'class' => 'bg-amber-100 text-amber-800'],
            self::STATUS_PLACED               => ['label' => 'Placed',               'class' => 'bg-green-100 text-green-800'],
            self::STATUS_REJECTED             => ['label' => 'Rejected',             'class' => 'bg-red-100 text-red-800'],
            self::STATUS_WITHDRAWN            => ['label' => 'Withdrawn',            'class' => 'bg-gray-100 text-gray-800'],
            default                           => ['label' => ucfirst($this->status), 'class' => 'bg-gray-100 text-gray-800'],
        };
    }

    /* ===============================
       RELATIONSHIPS
    =============================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo()
    {
        return $this->hasOne(ApplicantPersonalInfo::class);
    }

    public function academicInfo()
    {
        return $this->hasOne(ApplicantAcademicInfo::class);
    }

    public function preference()
    {
        return $this->hasOne(InternshipPreference::class);
    }

    public function internshipPreferences()
    {
        return $this->hasOne(InternshipPreference::class);
    }

    public function getMotivationLetterAttribute()
    {
        return $this->preference?->motivation_statement;
    }

    public function getCareerGoalsAttribute()
    {
        return $this->preference?->career_objectives;
    }

    public function placement()
    {
        return $this->hasOne(InternshipPlacement::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(InternshipOpportunity::class, 'opportunity_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function logs()
    {
        return $this->hasMany(ApplicationLog::class)->latest();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /* ===============================
       PROGRESS CALCULATION
    =============================== */

    public function getCompletedSections(): array
    {
        $completed = [];
        if ($this->personalInfo)  $completed[] = 'personal';
        if ($this->academicInfo)  $completed[] = 'academic';
        if ($this->preference && $this->preference->preferred_department_id) $completed[] = 'preferences';
        if ($this->preference && $this->preference->motivation_statement)    $completed[] = 'motivation';
        if ($this->documents()->count() > 0) $completed[] = 'documents';
        return $completed;
    }

    public function getTotalWizardSteps(): int
    {
        return 6;
    }

    public function getCurrentWizardStep(): int
    {
        $completed = $this->getCompletedSections();
        $has = fn($s) => in_array($s, $completed);

        if ($has('documents'))   return 6; // Review & Submit
        if ($has('motivation'))  return 5; // Documents
        if ($has('preferences')) return 4; // Motivation
        if ($has('academic'))    return 3; // Preferences
        if ($has('personal'))    return 2; // Academic
        return 1;
    }

    public function refreshProgress(): void
    {
        $score = 0;
        $total = 0;

        // 1. Personal
        $personalRequired = ['first_name', 'last_name', 'date_of_birth', 'gender', 'phone', 'address'];
        $total += count($personalRequired);
        if ($p = $this->personalInfo) {
            foreach ($personalRequired as $f) {
                if (!empty($p->$f)) $score++;
            }
        }

        // 2. Academic
        $academicRequired = ['school_name', 'program_of_study', 'field_of_study'];
        $total += count($academicRequired);
        if ($a = $this->academicInfo) {
            foreach ($academicRequired as $f) {
                if (!empty($a->$f)) $score++;
            }
        }

        // 3. Preferences
        $prefRequired = ['preferred_department_id', 'required_duration'];
        $total += count($prefRequired);
        if ($pref = $this->preference) {
            foreach ($prefRequired as $f) {
                if (!empty($pref->$f)) $score++;
            }
        }

        // 4. Motivation
        $motRequired = ['motivation_statement'];
        $total += count($motRequired);
        if ($pref = $this->preference) {
            foreach ($motRequired as $f) {
                if (!empty($pref->$f)) $score++;
            }
        }

        // 5. Documents
        $total += 1;
        if ($this->documents()->count() > 0) {
            $score += 1;
        }

        $percentage = $total > 0 ? intval(($score / $total) * 100) : 0;

        $this->update([
            'completion_percentage' => min($percentage, 100),
            'current_step'          => $this->getCurrentWizardStep(),
        ]);
    }
}
