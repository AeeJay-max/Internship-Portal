<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $student  = Auth::user()->student;
        $semester = Semester::current();

        $enrollments = $student->enrollments()
            ->where('semester_id', $semester?->id)
            ->with(['subject.scheduleSlots.lecturer'])
            ->get();

        // Build weekly schedule — group slots by day
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $schedule = [];

        foreach ($days as $day) {
            $schedule[$day] = collect();
        }

        foreach ($enrollments as $enrollment) {
            foreach ($enrollment->subject->scheduleSlots as $slot) {
                if (array_key_exists($slot->day, $schedule)) {
                    $schedule[$slot->day]->push([
                        'time_start' => $slot->time_start,
                        'time_end'   => $slot->time_end,
                        'subject'    => $enrollment->subject->name,
                        'code'       => $enrollment->subject->code,
                        'credits'    => $enrollment->subject->credits,
                        'lecturer'   => $slot->lecturer->fullTitle(),
                        'room'       => $slot->room,
                        'building'   => $slot->building,
                        'type'       => $slot->type,
                    ]);
                }
            }
        }

        // Sort each day by time
        foreach ($schedule as $day => $slots) {
            $schedule[$day] = $slots->sortBy('time_start')->values();
        }

        $today = now()->format('l');

        return view('portal.schedule', compact('schedule', 'days', 'today', 'semester'));
    }
}
