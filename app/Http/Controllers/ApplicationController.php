<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicantPersonalInfo;
use App\Models\ApplicantAcademicInfo;
use App\Models\InternshipPreference;
use App\Models\ApplicationDocument;
use App\Models\ApplicationLog;
use App\Models\DocumentType;
use App\Models\Department;
use App\Models\InternshipOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /* =====================================================
       PRIVATE HELPERS
    ===================================================== */

    private function getDraftOrRedirect(): Application|\Illuminate\Http\RedirectResponse
    {
        $draft = Application::where('user_id', Auth::id())
            ->where('status', Application::STATUS_DRAFT)
            ->latest()
            ->first();

        if (! $draft) {
            return redirect()->route('application.selectType')
                ->with('error', 'Please start an internship application first.');
        }

        return $draft;
    }

    /* =====================================================
       AUTOSAVE — real-time field saving via AJAX
    ===================================================== */

    public function autosave(Request $request)
    {
        try {
            $application = Application::where('user_id', Auth::id())
                ->where('status', Application::STATUS_DRAFT)
                ->latest()->first();

            if (!$application) {
                return response()->json(['ok' => false, 'reason' => 'no_draft'], 404);
            }

            $step = $request->input('step');
            $data = $request->input('data', []);

            if (empty($step) || empty($data)) {
                return response()->json(['ok' => false, 'reason' => 'empty_data']);
            }

            if ($step === 'personal') {
                $allowed = [
                    'first_name', 'middle_name', 'last_name', 'date_of_birth', 'gender',
                    'passport_number', 'national_id', 'country_of_birth', 'place_of_birth',
                    'resident_country', 'citizenship', 'nationality', 'marital_status',
                    'city', 'province', 'phone', 'address', 'emergency_contact_name', 'emergency_contact_phone'
                ];
                $filtered = array_filter(
                    array_intersect_key($data, array_flip($allowed)),
                    fn($v) => $v !== null
                );
                if (!empty($filtered)) {
                    ApplicantPersonalInfo::updateOrCreate(
                        ['application_id' => $application->id],
                        array_merge($filtered, ['application_id' => $application->id])
                    );
                }

            } elseif ($step === 'academic') {
                $allowed = [
                    'school_name', 'institution_type', 'program_of_study', 'field_of_study',
                    'current_year_level', 'academic_qualification', 'expected_graduation_date',
                    'institution_city', 'institution_country', 'gpa'
                ];
                $filtered = array_filter(
                    array_intersect_key($data, array_flip($allowed)),
                    fn($v) => $v !== null && $v !== ''
                );
                if (!empty($filtered)) {
                    ApplicantAcademicInfo::updateOrCreate(
                        ['application_id' => $application->id],
                        array_merge($filtered, ['application_id' => $application->id])
                    );
                }

            } elseif ($step === 'preferences' || $step === 'motivation') {
                $allowed = [
                    'preferred_department_id', 'second_preferred_department_id',
                    'area_of_internship', 'specialisation', 'preferred_start_date',
                    'preferred_end_date', 'required_duration', 'institution_required_duration',
                    'preferred_location', 'flexible_department', 'motivation_statement',
                    'career_objectives', 'internship_objectives', 'skills_to_develop', 'why_selected_department'
                ];
                $filtered = array_filter(
                    array_intersect_key($data, array_flip($allowed)),
                    fn($v) => $v !== null
                );
                if (!empty($filtered)) {
                    InternshipPreference::updateOrCreate(
                        ['application_id' => $application->id],
                        array_merge($filtered, ['application_id' => $application->id])
                    );
                }
            }

            $application->refresh()->refreshProgress();

            return response()->json([
                'ok'         => true,
                'percentage' => $application->fresh()->completion_percentage,
            ]);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'reason' => $e->getMessage()], 500);
        }
    }

    public function progressData()
    {
        $application = Application::where('user_id', Auth::id())
            ->where('status', Application::STATUS_DRAFT)
            ->latest()->first();

        if (!$application) {
            return response()->json(['percentage' => 0]);
        }

        $application->refresh()->refreshProgress();

        return response()->json([
            'percentage' => $application->fresh()->completion_percentage,
        ]);
    }

    /* =====================================================
       START / SELECT TYPE OF APPLICATION
    ===================================================== */

    public function selectType(Request $request)
    {
        $existing = Application::where('user_id', Auth::id())
            ->where('status', Application::STATUS_DRAFT)
            ->first();

        if ($existing) {
            return redirect()->route('application.personal');
        }

        $opportunityId = $request->query('opportunity');
        $opportunity = null;

        if ($opportunityId) {
            $opportunity = InternshipOpportunity::with('department')->where('status', 'open')->find($opportunityId);
        }

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $opportunities = InternshipOpportunity::with('department')->where('status', 'open')->latest()->get();

        return view('application.select-type', compact('opportunity', 'departments', 'opportunities'));
    }

    public function createFromType(Request $request)
    {
        $existing = Application::where('user_id', Auth::id())
            ->where('status', Application::STATUS_DRAFT)
            ->first();

        if ($existing) {
            return redirect()->route('application.personal');
        }

        $validated = $request->validate([
            'opportunity_id'          => 'nullable|exists:internship_opportunities,id',
            'preferred_department_id' => 'nullable|exists:departments,id',
        ]);

        $refNumber = Application::generateReferenceNumber();

        $application = Application::create([
            'user_id'          => Auth::id(),
            'reference_number' => $refNumber,
            'opportunity_id'   => $validated['opportunity_id'] ?? null,
            'status'           => Application::STATUS_DRAFT,
            'current_step'     => 1,
            'completion_percentage' => 0,
        ]);

        // Pre-populate preferred department if coming from specific opportunity or selection
        $prefDeptId = $validated['preferred_department_id'] ?? null;

        if (!$prefDeptId && !empty($validated['opportunity_id'])) {
            $opp = InternshipOpportunity::find($validated['opportunity_id']);
            if ($opp) {
                $prefDeptId = $opp->department_id;
            }
        }

        if ($prefDeptId) {
            InternshipPreference::create([
                'application_id'          => $application->id,
                'preferred_department_id' => $prefDeptId,
            ]);
        }

        return redirect()->route('application.personal');
    }

    public function destroyDraft(int $id)
    {
        $draft = Application::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', Application::STATUS_DRAFT)
            ->firstOrFail();

        $draft->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Draft internship application deleted.');
    }

    /* =====================================================
       STEP 1 — PERSONAL INFORMATION
    ===================================================== */

    public function personal()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        return view('application.personal', [
            'application' => $application,
            'personal'    => $application->personalInfo,
            'currentStep' => 1,
        ]);
    }

    public function storePersonal(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $validated = $request->validate([
            'first_name'              => 'required|string|max:150',
            'middle_name'             => 'nullable|string|max:150',
            'last_name'               => 'required|string|max:150',
            'date_of_birth'           => 'required|date|before:today',
            'gender'                  => 'required|in:male,female',
            'nationality'             => 'required|string|max:100',
            'passport_number'         => 'nullable|string|max:100',
            'national_id'             => 'required|string|max:100',
            'phone'                   => 'required|string|max:30',
            'address'                 => 'required|string',
            'city'                    => 'required|string|max:100',
            'province'                => 'required|string|max:100',
            'emergency_contact_name'  => 'required|string|max:200',
            'emergency_contact_phone' => 'required|string|max:30',
        ]);

        ApplicantPersonalInfo::updateOrCreate(
            ['application_id' => $application->id],
            $validated + ['application_id' => $application->id]
        );

        $application->refresh()->refreshProgress();

        return redirect()->route('application.academic');
    }

    /* =====================================================
       STEP 2 — ACADEMIC INFORMATION
    ===================================================== */

    public function academic()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        return view('application.academic', [
            'application' => $application,
            'academic'    => $application->academicInfo,
            'currentStep' => 2,
        ]);
    }

    public function storeAcademic(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $validated = $request->validate([
            'school_name'             => 'required|string|max:255',
            'institution_type'        => 'required|string|max:100',
            'program_of_study'        => 'required|string|max:255',
            'field_of_study'          => 'required|string|max:255',
            'current_year_level'      => 'required|string|max:100',
            'academic_qualification'  => 'required|string|max:100',
            'expected_graduation_date'=> 'nullable|date',
            'gpa'                     => 'nullable|string|max:50',
        ]);

        ApplicantAcademicInfo::updateOrCreate(
            ['application_id' => $application->id],
            $validated + ['application_id' => $application->id]
        );

        $application->refresh()->refreshProgress();

        return redirect()->route('application.preferences');
    }

    /* =====================================================
       STEP 3 — INTERNSHIP PREFERENCES
    ===================================================== */

    public function preferences()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('application.preferences', [
            'application' => $application,
            'preference'  => $application->preference,
            'departments' => $departments,
            'currentStep' => 3,
        ]);
    }

    public function storePreferences(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $validated = $request->validate([
            'preferred_department_id'        => 'required|exists:departments,id',
            'second_preferred_department_id' => 'nullable|exists:departments,id|different:preferred_department_id',
            'area_of_internship'             => 'nullable|string|max:255',
            'specialisation'                 => 'nullable|string|max:255',
            'preferred_start_date'           => 'required|date',
            'preferred_end_date'             => 'required|date',
            'required_duration'              => 'required|string|max:100',
            'institution_required_duration'  => 'nullable|string|max:100',
            'preferred_location'             => 'nullable|string|max:255',
            'flexible_department'            => 'required|boolean',
        ]);

        InternshipPreference::updateOrCreate(
            ['application_id' => $application->id],
            $validated + ['application_id' => $application->id]
        );

        $application->refresh()->refreshProgress();

        return redirect()->route('application.motivation');
    }

    /* =====================================================
       STEP 4 — MOTIVATION
    ===================================================== */

    public function motivation()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        return view('application.motivation', [
            'application' => $application,
            'preference'  => $application->preference,
            'currentStep' => 4,
        ]);
    }

    public function storeMotivation(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $validated = $request->validate([
            'motivation_statement'    => 'required|string|min:20',
            'career_objectives'       => 'nullable|string',
            'internship_objectives'   => 'nullable|string',
            'skills_to_develop'       => 'nullable|string',
            'why_selected_department' => 'nullable|string',
        ]);

        InternshipPreference::updateOrCreate(
            ['application_id' => $application->id],
            $validated + ['application_id' => $application->id]
        );

        $application->refresh()->refreshProgress();

        return redirect()->route('application.documents');
    }

    /* =====================================================
       STEP 5 — DOCUMENTS
    ===================================================== */

    public function documents()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $documentTypes = DocumentType::all();

        return view('application.documents', [
            'application'   => $application,
            'documentTypes' => $documentTypes,
            'currentStep'   => 5,
        ]);
    }

    public function storeDocuments(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $allTypes = DocumentType::all()->keyBy('code');
        $errors = [];
        $saved = 0;

        foreach ($allTypes as $code => $docType) {
            if (!$request->hasFile("documents.{$code}")) continue;

            $file  = $request->file("documents.{$code}");
            $mimes = implode(',', array_map('trim', explode(',', $docType->allowed_mimes)));
            $maxKb = $docType->max_size;

            $validator = \Illuminate\Support\Facades\Validator::make(
                ['file' => $file],
                ['file' => "file|mimes:{$mimes}|max:{$maxKb}"]
            );

            if ($validator->fails()) {
                $errors["documents.{$code}"] = $validator->errors()->first('file');
                continue;
            }

            $path = $file->store('documents', 'public');
            ApplicationDocument::updateOrCreate(
                ['application_id' => $application->id, 'document_type_id' => $docType->id],
                ['file_path' => $path, 'uploaded_at' => now()]
            );
            $saved++;
        }

        $application->refresh()->refreshProgress();

        if (!empty($errors)) {
            return redirect()->route('application.documents')
                ->withErrors($errors)
                ->with('warning', $saved . ' document(s) saved with errors below.');
        }

        return redirect()->route('application.review');
    }

    /* =====================================================
       STEP 6 — REVIEW & SUBMIT
    ===================================================== */

    public function review()
    {
        $application = Application::where('user_id', Auth::id())
            ->latest()
            ->first();

        if (! $application) {
            return redirect()->route('dashboard')->with('error', 'No application found.');
        }

        $application->load([
            'personalInfo',
            'academicInfo',
            'preference.preferredDepartment',
            'preference.secondPreferredDepartment',
            'opportunity.department',
            'documents.documentType',
        ]);

        $missing = [];

        if ($application->isDraft()) {
            if (! $application->personalInfo)
                $missing[] = ['section' => 'Personal Information', 'route' => route('application.personal')];

            if (! $application->academicInfo)
                $missing[] = ['section' => 'Academic Background', 'route' => route('application.academic')];

            if (! $application->preference || ! $application->preference->preferred_department_id)
                $missing[] = ['section' => 'Internship Preferences', 'route' => route('application.preferences')];

            if (! $application->preference || ! $application->preference->motivation_statement)
                $missing[] = ['section' => 'Motivation Statement', 'route' => route('application.motivation')];

            if ($application->documents->isEmpty())
                $missing[] = ['section' => 'Supporting Documents', 'route' => route('application.documents')];
        }

        return view('application.review', [
            'application' => $application,
            'currentStep' => 6,
            'missing'     => $missing,
            'canSubmit'   => $application->isDraft() && empty($missing),
            'isSubmitted' => ! $application->isDraft(),
        ]);
    }

    public function submit()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        if (!$application->reference_number) {
            $application->reference_number = Application::generateReferenceNumber();
        }

        $application->update([
            'status'                => Application::STATUS_SUBMITTED,
            'submitted_at'          => now(),
            'completion_percentage' => 100,
            'current_step'          => 6,
        ]);

        ApplicationLog::log($application->id, 'Internship application submitted by applicant', Auth::id());

        return redirect()->route('dashboard')
            ->with('success', 'Your internship application (' . $application->reference_number . ') has been submitted successfully.');
    }

    public function show(int $id)
    {
        $application = Application::where('user_id', Auth::id())
            ->with([
                'personalInfo',
                'academicInfo',
                'preference.preferredDepartment',
                'preference.secondPreferredDepartment',
                'opportunity.department',
                'documents.documentType',
                'placement.department',
                'logs.performer',
            ])->findOrFail($id);

        return view('application.show', compact('application'));
    }

    public function reuploadDocuments(Request $request, int $id)
    {
        $application = Application::where('user_id', Auth::id())
            ->where('status', Application::STATUS_DOCUMENTS_REQUESTED)
            ->findOrFail($id);

        $flaggedIds   = $application->flagged_document_ids ?? [];
        $flaggedTypes = DocumentType::whereIn('id', $flaggedIds)->get()->keyBy('code');

        foreach ($request->file('documents', []) as $code => $file) {
            if (!$file || !isset($flaggedTypes[$code])) continue;
            $path = $file->store('documents', 'public');
            ApplicationDocument::updateOrCreate(
                ['application_id' => $application->id, 'document_type_id' => $flaggedTypes[$code]->id],
                ['file_path' => $path, 'uploaded_at' => now()]
            );
        }

        $application->update([
            'status'                => Application::STATUS_SUBMITTED,
            'needs_reupload_review' => true,
        ]);

        ApplicationLog::log($application->id, 'Requested documents re-uploaded by applicant.', Auth::id());

        return redirect()->route('dashboard')->with('success', 'Requested documents re-uploaded successfully.');
    }
}
