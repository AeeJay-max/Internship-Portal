<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSlot;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\Program;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    const CLASS_HOURS = [
        '1' => ['label' => '1st Hour', 'start' => '09:30', 'end' => '10:50'],
        '2' => ['label' => '2nd Hour', 'start' => '11:00', 'end' => '12:20'],
        '3' => ['label' => '3rd Hour', 'start' => '12:50', 'end' => '14:10'],
        '4' => ['label' => '4th Hour', 'start' => '14:20', 'end' => '15:40'],
    ];

    const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function index(Request $request)
    {
        $semesters       = Semester::orderByDesc('starts_at')->get();
        $currentSemester = Semester::current() ?? $semesters->first();
        $semesterId      = $request->get('semester_id', $currentSemester?->id);
        $programId       = $request->get('program_id');
        $yearOfStudy     = $request->get('year_of_study');

        $programs = Program::whereHas('subjects', fn($q) => $q->where('semester_id', $semesterId))
            ->withCount(['subjects as subjectCount' => fn($q) => $q->where('semester_id', $semesterId)])
            ->orderBy('degree_level')
            ->orderBy('name')
            ->get()
            ->groupBy('degree_level');

        $selectedProgram  = null;
        $subjects         = collect();
        $lecturers        = Lecturer::orderBy('name')->get();
        $calendarData     = [];
        $availableSubjects = collect(); // subjects that still need more slots

        if ($programId) {
            $selectedProgram = Program::find($programId);

            $subjectQuery = Subject::with(['scheduleSlots.lecturer'])
                ->where('semester_id', $semesterId)
                ->where('program_id', $programId)
                ->orderBy('year_of_study')
                ->orderBy('subject_type')
                ->orderBy('code');
            if ($yearOfStudy) { $subjectQuery->where('year_of_study', $yearOfStudy); }
            $subjects = $subjectQuery->get();

            // Build calendar grid: day → hour → slot data
            foreach (self::DAYS as $day) {
                $calendarData[$day] = [];
                foreach (self::CLASS_HOURS as $num => $h) {
                    $calendarData[$day][$num] = null;
                }
            }

            foreach ($subjects as $subject) {
                foreach ($subject->scheduleSlots as $slot) {
                    foreach (self::CLASS_HOURS as $num => $h) {
                        if ($slot->time_start_short === $h['start']) {
                            $calendarData[$slot->day][$num] = [
                                'slot'    => $slot,
                                'subject' => $subject,
                            ];
                            break;
                        }
                    }
                }
            }

            // Subjects that still need more slots (for clickable cell modal)
            $availableSubjects = $subjects->filter(fn($s) => !$s->isFullyScheduled())
                ->sortBy('subject_type');
        }

        return view('admin.portal.schedule.index', compact(
            'semesters', 'semesterId', 'currentSemester',
            'programs', 'programId', 'selectedProgram',
            'subjects', 'lecturers', 'calendarData',
            'availableSubjects', 'yearOfStudy'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'day'         => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time_start'  => 'required|date_format:H:i',
            'time_end'    => 'required|date_format:H:i',
            'building'    => 'nullable|string|max:100',
            'room'        => 'nullable|string|max:50',
            'type'        => 'required|in:lecture,seminar,lab',
        ]);

        // Check for conflicts — same day/time already taken
        $conflict = ScheduleSlot::whereHas('subject', fn($q) =>
        $q->where('program_id', Subject::find($request->subject_id)?->program_id)
            ->where('semester_id', Subject::find($request->subject_id)?->semester_id)
        )
            ->where('day', $request->day)
            ->where('time_start', $request->time_start . ':00')
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'A slot already exists for this program at that day and hour.']);
        }

        ScheduleSlot::create($request->only(
            'subject_id', 'lecturer_id', 'day', 'time_start', 'time_end', 'building', 'room', 'type'
        ));

        return back()->with('success', 'Slot added to schedule.');
    }

    public function update(Request $request, ScheduleSlot $slot)
    {
        $request->validate([
            'lecturer_id' => 'required|exists:lecturers,id',
            'day'         => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time_start'  => 'required|date_format:H:i',
            'time_end'    => 'required|date_format:H:i',
            'building'    => 'nullable|string|max:100',
            'room'        => 'nullable|string|max:50',
            'type'        => 'required|in:lecture,seminar,lab',
        ]);

        $slot->update($request->only(
            'lecturer_id', 'day', 'time_start', 'time_end', 'building', 'room', 'type'
        ));

        return back()->with('success', 'Slot updated.');
    }

    public function destroy(ScheduleSlot $slot)
    {
        $slot->delete();
        return back()->with('success', 'Slot removed.');
    }
}
