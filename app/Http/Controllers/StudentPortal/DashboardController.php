<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student  = Auth::user()->student;
        $semester = Semester::current();

        // Current enrollments with subjects, schedule and grades
        $enrollments = $student->enrollments()
            ->where('semester_id', $semester?->id)
            ->with(['subject.scheduleSlots.lecturer', 'grade', 'subject.program'])
            ->get();

        // Today's schedule
        $today       = now()->format('l'); // e.g. "Monday"
        $todaySlots  = collect();

        foreach ($enrollments as $enrollment) {
            foreach ($enrollment->subject->scheduleSlots as $slot) {
                if ($slot->day === $today) {
                    $todaySlots->push([
                        'time_start' => $slot->time_start,
                        'time_end'   => $slot->time_end,
                        'subject'    => $enrollment->subject->name,
                        'code'       => $enrollment->subject->code,
                        'lecturer'   => $slot->lecturer->fullTitle(),
                        'room'       => $slot->room,
                        'building'   => $slot->building,
                        'type'       => $slot->type,
                    ]);
                }
            }
        }

        $todaySlots = $todaySlots->sortBy('time_start')->values();

        // GPA
        $semesterGpa    = $student->semesterGpa($semester?->id);
        $cumulativeGpa  = $student->cumulativeGpa();
        $totalCredits   = $student->totalCredits();
        $standing       = $student->academicStanding();

        // Latest announcements
        $announcements = News::published()->latest('published_at')->limit(3)->get();

        return view('portal.dashboard', compact(
            'student',
            'semester',
            'enrollments',
            'todaySlots',
            'semesterGpa',
            'cumulativeGpa',
            'totalCredits',
            'standing',
            'announcements'
        ));
    }
}
