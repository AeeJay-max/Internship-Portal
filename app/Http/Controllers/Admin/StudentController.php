<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'application.program', 'application.admissionCycle']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        // Filter by program
        if ($request->filled('program')) {
            $query->whereHas('application', fn($q) =>
            $q->where('program_id', $request->program)
            );
        }

        // Filter by degree level
        if ($request->filled('level')) {
            $query->whereHas('application.program', fn($q) =>
            $q->where('degree_level', $request->level)
            );
        }

        // Filter by year of study
        if ($request->filled('year')) {
            $query->where('current_year', $request->year);
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        // Data for filter dropdowns
        $programs   = \App\Models\Program::where('is_active', true)->orderBy('name')->get();
        $maxYear    = 5;

        // Stats for summary cards
        $totalStudents  = Student::count();
        $byLevel = Student::whereHas('application.program')
            ->with('application.program')
            ->get()
            ->groupBy(fn($s) => $s->application?->program?->degree_level);

        return view('admin.students.index', compact('students', 'programs', 'maxYear', 'totalStudents', 'byLevel'));
    }

    public function show(int $id)
    {
        $student = Student::with([
            'user',
            'application.personalInfo',
            'application.academicInfo',
            'application.program',
            'application.admissionCycle',
            'application.logs.performer',
            'enrollments.subject',
            'enrollments.semester',
            'enrollments.grade',
        ])->findOrFail($id);

        return view('admin.students.show', compact('student'));
    }

    public function updatePhoto(Request $request, int $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $student = Student::with('user')->findOrFail($id);

        if (!$student->user) {
            return back()->with('error', 'Student user account not found.');
        }

        // Delete old photo
        if ($student->user->profile_photo) {
            Storage::disk('public')->delete($student->user->profile_photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        $student->user->update(['profile_photo' => $path]);

        return back()->with('success', 'Student photo updated successfully.');
    }

    public function promoteYear(int $id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $student = Student::with('application')->findOrFail($id);
        $newYear  = ($student->current_year ?? 1) + 1;

        $student->update(['current_year' => $newYear]);

        $currentSemester = \App\Models\Semester::where('is_current', true)->first();

        $enrolledCount = 0;
        if ($currentSemester && $student->application?->program_id) {
            $subjects = \App\Models\Subject::where('program_id', $student->application->program_id)
                ->where('semester_id', $currentSemester->id)
                ->where('year_of_study', $newYear)
                ->get();

            foreach ($subjects as $subject) {
                $created = \App\Models\StudentSubjectEnrollment::firstOrCreate([
                    'student_id'  => $student->id,
                    'subject_id'  => $subject->id,
                    'semester_id' => $currentSemester->id,
                ], ['status' => 'enrolled']);
                if ($created->wasRecentlyCreated) $enrolledCount++;
            }
        }

        return back()->with('success', "Promoted {$student->user->name} to Year {$newYear}. Enrolled in {$enrolledCount} new subject(s).");
    }
}
