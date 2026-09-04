<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'student_number',
        'enrollment_date',
        'current_year',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'current_year'    => 'integer',
    ];

    /* ===============================
       RELATIONSHIPS
    =============================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentSubjectEnrollment::class);
    }

    public function currentEnrollments(): HasMany
    {
        $currentSemester = Semester::current();
        return $this->hasMany(StudentSubjectEnrollment::class)
            ->where('semester_id', $currentSemester?->id)
            ->with(['subject.scheduleSlots.lecturer', 'grade']);
    }

    /* ===============================
       COMPUTED ATTRIBUTES
    =============================== */

    public function getProgramAttribute()
    {
        return $this->application?->program;
    }

    // Calculate semester GPA for a given semester
    public function semesterGpa(?int $semesterId = null): ?float
    {
        $semesterId = $semesterId ?? Semester::current()?->id;
        if (!$semesterId) return null;

        $enrollments = $this->enrollments()
            ->where('semester_id', $semesterId)
            ->with('grade', 'subject')
            ->get();

        $totalPoints  = 0;
        $totalCredits = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade && $enrollment->grade->gpa_points !== null) {
                $credits       = $enrollment->subject->credits;
                $totalPoints  += $enrollment->grade->gpa_points * $credits;
                $totalCredits += $credits;
            }
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : null;
    }

    // Calculate cumulative GPA across all semesters
    public function cumulativeGpa(): ?float
    {
        $enrollments = $this->enrollments()
            ->with('grade', 'subject')
            ->get();

        $totalPoints  = 0;
        $totalCredits = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->grade && $enrollment->grade->gpa_points !== null) {
                $credits       = $enrollment->subject->credits;
                $totalPoints  += $enrollment->grade->gpa_points * $credits;
                $totalCredits += $credits;
            }
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : null;
    }

    // Total credits enrolled across all semesters
    public function totalCredits(): int
    {
        return $this->enrollments()
            ->with('subject')
            ->get()
            ->sum(fn($e) => $e->subject->credits ?? 0);
    }

    // Academic standing based on GPA
    public function academicStanding(): string
    {
        $gpa = $this->cumulativeGpa();
        if ($gpa === null) return 'N/A';
        if ($gpa >= 3.50)  return 'Excellent Standing';
        if ($gpa >= 3.00)  return 'Good Standing';
        if ($gpa >= 2.00)  return 'Satisfactory';
        return 'Academic Probation';
    }

    /* ===============================
       STUDENT NUMBER GENERATOR
       Format: MOSRAC-YYYY-XXXX
    =============================== */

    public static function generateStudentNumber(): string
    {
        $year   = now()->year;
        $prefix = "MOSRAC-{$year}-";

        $last = self::where('student_number', 'like', $prefix . '%')
            ->orderByDesc('student_number')
            ->value('student_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
