<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Program;
use App\Models\Lecturer;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;

class SubjectController extends Controller
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
        $subjects        = collect();

        if ($programId) {
            $selectedProgram = Program::find($programId);
            $subjectQuery = Subject::with(['program', 'semester', 'scheduleSlots.lecturer'])
                ->withCount('enrollments')
                ->where('semester_id', $semesterId)
                ->where('program_id', $programId)
                ->orderBy('year_of_study')
                ->orderBy('subject_type')
                ->orderBy('code');
            if ($yearOfStudy) { $subjectQuery->where('year_of_study', $yearOfStudy); }
            $subjects = $subjectQuery->get();
        }

        return view('admin.portal.subjects.index', compact(
            'semesters', 'semesterId', 'currentSemester',
            'programs', 'programId', 'selectedProgram', 'subjects', 'yearOfStudy'
        ));
    }

    public function create(Request $request)
    {
        $this->requireSuperAdmin();
        $semesters       = Semester::orderByDesc('starts_at')->get();
        $currentSemester = Semester::current() ?? $semesters->first();
        $semesterId      = $request->get('semester_id', $currentSemester?->id);
        $programs        = Program::where('is_active', true)->orderBy('degree_level')->orderBy('name')->get();
        $lecturers       = Lecturer::orderBy('name')->get();
        $preProgram      = $request->get('program_id') ? Program::find($request->get('program_id')) : null;

        // Build code map: program_id → [existing codes] so JS can suggest next code
        $existingCodes = Subject::where('semester_id', $semesterId)
            ->whereIn('program_id', $programs->pluck('id'))
            ->select('program_id', 'code')
            ->get()
            ->groupBy('program_id')
            ->map(fn($group) => $group->pluck('code')->toArray());

        // Build program prefix map: program_id → prefix
        // e.g. Civil Engineering → CE, Computer Science & Engineering → CSE
        $programPrefixes = $programs->mapWithKeys(function($p) {
            $words  = preg_split('/[\s&]+/', $p->name);
            $prefix = implode('', array_map(fn($w) => strtoupper(substr($w, 0, 1)), array_filter($words)));
            if (strlen($prefix) < 2) $prefix = strtoupper(substr($p->name, 0, 3));
            return [$p->id => $prefix];
        });

        return view('admin.portal.subjects.create', compact(
            'semesters', 'programs', 'lecturers', 'preProgram',
            'semesterId', 'existingCodes', 'programPrefixes'
        ));
    }

    public function store(Request $request)
    {
        $this->requireSuperAdmin();

        $request->validate([
            'code'          => 'required|string|max:20',
            'name'          => 'required|string|max:255',
            'credits'       => 'required|integer|min:1|max:10',
            'hours_per_week'=> 'required|integer|min:1|max:6',
            'subject_type'  => 'required|in:major_core,major_elective,general',
            'year_of_study' => 'required|integer|min:1|max:5',
            'semester_id'   => 'required|exists:semesters,id',
            'program_id'    => 'required|exists:programs,id',
            'description'   => 'nullable|string',
        ]);

        $subject = Subject::create($request->only(
            'code', 'name', 'credits', 'hours_per_week', 'subject_type',
            'year_of_study', 'semester_id', 'program_id', 'description'
        ));

        return redirect()->route('admin.portal.subjects.show', $subject)
            ->with('success', 'Subject created.');
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'program', 'semester',
            'scheduleSlots.lecturer',
            'enrollments.student.user',
            'enrollments.grade',
        ]);
        $lecturers = Lecturer::orderBy('name')->get();
        return view('admin.portal.subjects.show', compact('subject', 'lecturers'));
    }

    public function edit(Subject $subject)
    {
        $this->requireSuperAdmin();
        $semesters = Semester::orderByDesc('starts_at')->get();
        $programs  = Program::where('is_active', true)->orderBy('name')->get();
        return view('admin.portal.subjects.edit', compact('subject', 'semesters', 'programs'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->requireSuperAdmin();

        $request->validate([
            'code'          => 'required|string|max:20',
            'name'          => 'required|string|max:255',
            'credits'       => 'required|integer|min:1|max:10',
            'hours_per_week'=> 'required|integer|min:1|max:6',
            'subject_type'  => 'required|in:major_core,major_elective,general',
            'year_of_study' => 'required|integer|min:1|max:5',
            'semester_id'   => 'required|exists:semesters,id',
            'program_id'    => 'required|exists:programs,id',
            'description'   => 'nullable|string',
        ]);

        $subject->update($request->only(
            'code', 'name', 'credits', 'hours_per_week', 'subject_type',
            'year_of_study', 'semester_id', 'program_id', 'description'
        ));

        return redirect()->route('admin.portal.subjects.show', $subject)
            ->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $this->requireSuperAdmin();
        $subject->delete();
        return redirect()->route('admin.portal.subjects.index')->with('success', 'Subject deleted.');
    }

    private function requireSuperAdmin()
    {
        if (!auth()->user()->isSuperAdmin()) abort(403);
    }
}
