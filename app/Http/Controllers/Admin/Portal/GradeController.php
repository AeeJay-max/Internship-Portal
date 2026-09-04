<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Program;
use App\Models\StudentSubjectEnrollment;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $semesters       = Semester::orderByDesc('starts_at')->get();
        $currentSemester = Semester::current() ?? $semesters->first();
        $semesterId    = $request->get('semester_id', $currentSemester?->id);
        $programId     = $request->get('program_id');
        $yearOfStudy   = $request->get('year_of_study'); // null = all years

        // Only programs that have subjects in this semester, grouped by degree level
        $programs = Program::whereHas('subjects', fn($q) => $q->where('semester_id', $semesterId))
            ->withCount(['subjects as subjectCount' => fn($q) => $q->where('semester_id', $semesterId)])
            ->orderBy('degree_level')
            ->orderBy('name')
            ->get()
            ->groupBy('degree_level');

        // Subjects only load when a program is selected
        $subjects        = collect();
        $selectedProgram = null;

        if ($programId) {
            $selectedProgram = Program::find($programId);
            $subjectQuery = Subject::with(['enrollments.grade', 'enrollments.student.user'])
                ->where('semester_id', $semesterId)
                ->where('program_id', $programId)
                ->withCount('enrollments')
                ->orderBy('year_of_study')
                ->orderBy('code');

            // Filter by year if selected
            if ($yearOfStudy) {
                $subjectQuery->where('year_of_study', $yearOfStudy);
            }

            $subjects = $subjectQuery->get();
        }

        return view('admin.portal.grades.index', compact(
            'semesters', 'semesterId', 'currentSemester',
            'programs', 'programId', 'selectedProgram', 'subjects',
            'yearOfStudy'
        ));
    }

    public function subject(Subject $subject)
    {
        $subject->load([
            'program', 'semester',
            'scheduleSlots.lecturer',
            'enrollments.student.user',
            'enrollments.grade',
        ]);

        return view('admin.portal.grades.subject', compact('subject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:student_subject_enrollments,id',
            'midterm'       => 'nullable|numeric|min:0|max:40',
            'final'         => 'nullable|numeric|min:0|max:60',
        ]);

        $enrollment = StudentSubjectEnrollment::findOrFail($request->enrollment_id);
        $midterm    = $request->midterm ?? 0;
        $final      = $request->final ?? 0;
        $calculated = Grade::calculate($midterm, $final, 0);

        Grade::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            array_merge(['midterm' => $midterm, 'final' => $final, 'participation' => 0], $calculated)
        );

        return back()->with('success', 'Grade saved.');
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'midterm' => 'nullable|numeric|min:0|max:40',
            'final'   => 'nullable|numeric|min:0|max:60',
        ]);

        $midterm    = $request->midterm ?? $grade->midterm ?? 0;
        $final      = $request->final ?? $grade->final ?? 0;
        $calculated = Grade::calculate($midterm, $final, 0);

        $grade->update(array_merge(['midterm' => $midterm, 'final' => $final], $calculated));

        return back()->with('success', 'Grade updated.');
    }
}
