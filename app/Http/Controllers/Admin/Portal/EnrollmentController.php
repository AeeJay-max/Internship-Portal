<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\StudentSubjectEnrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Program;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $semesters       = Semester::orderByDesc('starts_at')->get();
        $currentSemester = Semester::current() ?? $semesters->first();
        $semesterId      = $request->get('semester_id', $currentSemester?->id);
        $programId       = $request->get('program_id');
        $yearOfStudy     = $request->get('year_of_study');

        // Programs that have subjects this semester
        $programs = Program::whereHas('subjects', fn($q) => $q->where('semester_id', $semesterId))
            ->withCount(['subjects as subjectCount' => fn($q) => $q->where('semester_id', $semesterId)])
            ->orderBy('degree_level')
            ->orderBy('name')
            ->get()
            ->groupBy('degree_level');

        $selectedProgram = null;
        $programStudents = collect();
        $subjects        = collect();

        if ($programId) {
            $selectedProgram = Program::find($programId);

            // All subjects for this program this semester
            $subjectQuery = Subject::where('program_id', $programId)
                ->where('semester_id', $semesterId)
                ->orderBy('year_of_study')
                ->orderBy('code');
            if ($yearOfStudy) { $subjectQuery->where('year_of_study', $yearOfStudy); }
            $subjects = $subjectQuery->get();

            // Students enrolled in this program via approved applications
            $programStudents = Student::with(['user', 'application'])
                ->whereHas('application', fn($q) => $q->where('program_id', $programId))
                ->orderBy('student_number')
                ->get()
                ->map(function ($student) use ($subjects, $semesterId) {
                    // Check which subjects this student is enrolled in
                    $enrolledIds = StudentSubjectEnrollment::where('student_id', $student->id)
                        ->where('semester_id', $semesterId)
                        ->pluck('subject_id')
                        ->toArray();

                    $student->enrolledSubjectIds = $enrolledIds;
                    $student->missingSubjects    = $subjects->whereNotIn('id', $enrolledIds);
                    $student->enrolledCount      = count($enrolledIds);
                    return $student;
                });
        }

        return view('admin.portal.enrollments.index', compact(
            'semesters', 'semesterId', 'currentSemester',
            'programs', 'programId', 'selectedProgram',
            'programStudents', 'subjects', 'yearOfStudy'
        ));
    }

    /**
     * Sync a student — enroll them in all subjects of their program they're missing
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $student  = Student::with('application')->findOrFail($request->student_id);
        $programId = $student->application?->program_id;

        if (!$programId) {
            return back()->withErrors(['error' => 'Student has no program assigned.']);
        }

        $subjects = Subject::where('program_id', $programId)
            ->where('semester_id', $request->semester_id)
            ->get();

        $enrolled = 0;
        foreach ($subjects as $subject) {
            $created = StudentSubjectEnrollment::firstOrCreate([
                'student_id'  => $student->id,
                'subject_id'  => $subject->id,
                'semester_id' => $request->semester_id,
            ], ['status' => 'enrolled']);
            if ($created->wasRecentlyCreated) $enrolled++;
        }

        return back()->with('success', "Synced {$student->user->name} — {$enrolled} new subject(s) enrolled.");
    }

    /**
     * Sync ALL students in a program
     */
    public function syncProgram(Request $request)
    {
        $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $subjects = Subject::where('program_id', $request->program_id)
            ->where('semester_id', $request->semester_id)
            ->get();

        $students = Student::whereHas('application', fn($q) => $q->where('program_id', $request->program_id))
            ->get();

        $totalEnrolled = 0;
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                $created = StudentSubjectEnrollment::firstOrCreate([
                    'student_id'  => $student->id,
                    'subject_id'  => $subject->id,
                    'semester_id' => $request->semester_id,
                ], ['status' => 'enrolled']);
                if ($created->wasRecentlyCreated) $totalEnrolled++;
            }
        }

        return back()->with('success', "All {$students->count()} students synced — {$totalEnrolled} new enrollment(s) created.");
    }

    public function destroy(StudentSubjectEnrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Enrollment removed.');
    }
}
