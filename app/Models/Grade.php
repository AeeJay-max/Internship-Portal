<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'midterm',
        'final',
        'participation',
        'total',
        'letter_grade',
        'gpa_points',
    ];

    protected $casts = [
        'midterm'       => 'decimal:1',
        'final'         => 'decimal:1',
        'participation' => 'decimal:1',
        'total'         => 'decimal:1',
        'gpa_points'    => 'decimal:2',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(StudentSubjectEnrollment::class, 'enrollment_id');
    }

    // Auto calculate total, letter grade and GPA points from midterm + final + participation
    public static function calculate(float $midterm, float $final, float $participation = 0): array
    {
        $total = $midterm + $final + $participation;

        if ($total >= 90)      { $letter = 'A';  $gpa = 4.00; }
        elseif ($total >= 85)  { $letter = 'B+'; $gpa = 3.50; }
        elseif ($total >= 80)  { $letter = 'B';  $gpa = 3.00; }
        elseif ($total >= 75)  { $letter = 'C+'; $gpa = 2.50; }
        elseif ($total >= 70)  { $letter = 'C';  $gpa = 2.00; }
        elseif ($total >= 60)  { $letter = 'D';  $gpa = 1.00; }
        else                   { $letter = 'F';  $gpa = 0.00; }

        return [
            'total'        => $total,
            'letter_grade' => $letter,
            'gpa_points'   => $gpa,
        ];
    }
}
