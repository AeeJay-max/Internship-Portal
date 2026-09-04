<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    public function index()
    {
        $student   = Auth::user()->student;
        $semesters = Semester::orderByDesc('starts_at')->get();

        // All enrollments grouped by semester
        $allEnrollments = $student->enrollments()
            ->with(['subject', 'grade', 'semester'])
            ->get()
            ->groupBy('semester_id');

        // Build semester summaries
        $semesterData = [];
        foreach ($semesters as $semester) {
            $enrollments = $allEnrollments->get($semester->id, collect());
            if ($enrollments->isEmpty()) continue;

            $gpa = $student->semesterGpa($semester->id);

            $semesterData[] = [
                'semester'    => $semester,
                'enrollments' => $enrollments,
                'gpa'         => $gpa,
                'credits'     => $enrollments->sum(fn($e) => $e->subject->credits ?? 0),
            ];
        }

        $cumulativeGpa = $student->cumulativeGpa();
        $totalCredits  = $student->totalCredits();
        $standing      = $student->academicStanding();

        return view('portal.grades.index', compact(
            'student',
            'semesterData',
            'cumulativeGpa',
            'totalCredits',
            'standing'
        ));
    }

    public function transcript()
    {
        $student   = Auth::user()->student;
        $semesters = Semester::orderBy('starts_at')->get();

        $allEnrollments = $student->enrollments()
            ->with(['subject.program', 'grade', 'semester'])
            ->get()
            ->groupBy('semester_id');

        $semesterData = [];
        foreach ($semesters as $semester) {
            $enrollments = $allEnrollments->get($semester->id, collect());
            if ($enrollments->isEmpty()) continue;

            $semesterData[] = [
                'semester'    => $semester,
                'enrollments' => $enrollments,
                'gpa'         => $student->semesterGpa($semester->id),
                'credits'     => $enrollments->sum(fn($e) => $e->subject->credits ?? 0),
            ];
        }

        return view('portal.grades.transcript', compact(
            'student',
            'semesterData',
            'semesterData'
        ));
    }
}
