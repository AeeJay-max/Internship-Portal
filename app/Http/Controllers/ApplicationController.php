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
        $userId = Auth::id();

        $existing = Application::where('user_id', $userId)
            ->where('status', Application::STATUS_DRAFT)
            ->first();

        if ($existing) {
            return redirect()->route('application.personal');
        }

        $submitted = Application::where('user_id', $userId)
            ->where('status', '!=', Application::STATUS_DRAFT)
            ->exists();

        if ($submitted && !$request->has('opportunity')) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already submitted an internship application. You can track your application progress in your portal dashboard.');
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
        $userId = Auth::id();

        $existing = Application::where('user_id', $userId)
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
            'user_id'               => $userId,
            'reference_number'      => $refNumber,
            'opportunity_id'        => $validated['opportunity_id'] ?? null,
            'status'                => Application::STATUS_DRAFT,
            'current_step'          => 1,
            'completion_percentage' => 0,
        ]);

        // Auto-populate details from previous application if applicant has applied before
        $previousApp = Application::where('user_id', $userId)
            ->where('id', '!=', $application->id)
            ->latest()
            ->first();

        if ($previousApp) {
            if ($previousApp->personalInfo) {
                $data = $previousApp->personalInfo->toArray();
                unset($data['id'], $data['application_id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
                ApplicantPersonalInfo::create(array_merge($data, ['application_id' => $application->id]));
            }

            if ($previousApp->academicInfo) {
                $data = $previousApp->academicInfo->toArray();
                unset($data['id'], $data['application_id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
                ApplicantAcademicInfo::create(array_merge($data, ['application_id' => $application->id]));
            }

            if ($previousApp->preference) {
                $data = $previousApp->preference->toArray();
                unset($data['id'], $data['application_id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
                if (!empty($validated['opportunity_id'])) {
                    $opp = InternshipOpportunity::find($validated['opportunity_id']);
                    if ($opp) {
                        $data['preferred_department_id'] = $opp->department_id;
                    }
                } elseif (!empty($validated['preferred_department_id'])) {
                    $data['preferred_department_id'] = $validated['preferred_department_id'];
                }
                InternshipPreference::create(array_merge($data, ['application_id' => $application->id]));
            }

            foreach ($previousApp->documents as $doc) {
                $docData = $doc->toArray();
                unset($docData['id'], $docData['application_id'], $docData['created_at'], $docData['updated_at'], $docData['deleted_at']);
                ApplicationDocument::create(array_merge($docData, ['application_id' => $application->id]));
            }

            $application->refreshProgress();

            if (!empty($validated['opportunity_id']) || $previousApp) {
                $application->update([
                    'status'       => Application::STATUS_SUBMITTED,
                    'submitted_at' => now(),
                ]);

                $opp = !empty($validated['opportunity_id']) ? InternshipOpportunity::find($validated['opportunity_id']) : null;
                $oppTitle = $opp ? $opp->title : 'General Internship';

                ApplicationLog::create([
                    'application_id' => $application->id,
                    'action'         => 'submitted',
                    'notes'          => 'Application automatically populated and submitted to Ministry for: ' . $oppTitle,
                    'performed_by'   => Auth::id(),
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Your application for "' . $oppTitle . '" has been automatically filled using your saved information and submitted to the Ministry.');
            }
        } else {
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
       STEP 5 — DOCUMENTS (SINGLE COMBINED PDF REQUIREMENT)
    ===================================================== */

    public function documents()
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $currentDocument = $application->documents()->latest()->first();

        return view('application.documents', [
            'application'     => $application,
            'currentDocument' => $currentDocument,
            'currentStep'     => 5,
        ]);
    }

    public function storeDocuments(Request $request)
    {
        $application = $this->getDraftOrRedirect();
        if ($application instanceof \Illuminate\Http\RedirectResponse) return $application;

        $existingDoc = $application->documents()->latest()->first();

        // If user already has uploaded PDF and didn't select a new file, proceed
        if ($existingDoc && !$request->hasFile('combined_document')) {
            $application->refreshProgress();
            return redirect()->route('application.review');
        }

        $request->validate([
            'combined_document' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240', // 10MB limit
            ],
        ], [
            'combined_document.required' => 'Please select your single combined supporting documents PDF before proceeding.',
            'combined_document.mimes'    => 'Only PDF files are accepted. Please combine all required documents into one PDF file.',
            'combined_document.max'      => 'The combined PDF file size must not exceed 10MB.',
        ]);

        $file = $request->file('combined_document');

        if (!$file || !$file->isValid() || strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return back()->withErrors(['combined_document' => 'Invalid file. Only valid PDF files are accepted.'])->withInput();
        }

        $path = $file->store('documents', 'public');

        // Delete any legacy individual document records and save single combined PDF
        $application->documents()->delete();

        ApplicationDocument::create([
            'application_id' => $application->id,
            'cert_name'      => 'Combined Supporting Documents (National ID, Transcripts, Current Results, CV, University Letter)',
            'file_path'      => $path,
            'uploaded_at'    => now(),
            'is_verified'    => false,
        ]);

        $application->refreshProgress();

        return redirect()->route('application.review')->with('success', 'Single combined supporting documents PDF uploaded successfully.');
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
            'documents',
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
                $missing[] = ['section' => 'Combined Supporting Documents PDF', 'route' => route('application.documents')];
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

        if ($application->documents->isEmpty()) {
            return redirect()->route('application.documents')
                ->with('error', 'Please upload your combined supporting documents PDF before submitting your application.');
        }

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

    public function viewDocument(int $id)
    {
        $doc = ApplicationDocument::findOrFail($id);
        $app = $doc->application;

        if ($app->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to document.');
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $filePath = $doc->file_path;

        if (!$filePath || !$disk->exists($filePath)) {
            $filePath = 'documents/test_placeholder.pdf';
            if (!$disk->exists($filePath)) {
                $this->ensurePlaceholderPdfExists($disk->path($filePath));
            }
        }

        $fullPath = $disk->path($filePath);
        $filename = \Illuminate\Support\Str::slug($app->reference_number ?: 'MoSRAC_Application') . '_Supporting_Documents.pdf';

        $response = response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
        ]);
        $response->setContentDisposition(\Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE, $filename);

        return $response;
    }

    public function downloadDocument(int $id, Request $request)
    {
        $doc = ApplicationDocument::findOrFail($id);
        $app = $doc->application;

        if ($app->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to document.');
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $filePath = $doc->file_path;

        if (!$filePath || !$disk->exists($filePath)) {
            $filePath = 'documents/test_placeholder.pdf';
            if (!$disk->exists($filePath)) {
                $this->ensurePlaceholderPdfExists($disk->path($filePath));
            }
        }

        $fullPath = $disk->path($filePath);
        $filename = \Illuminate\Support\Str::slug($app->reference_number ?: 'MoSRAC_Application') . '_Supporting_Documents.pdf';

        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function ensurePlaceholderPdfExists(string $fullPath): void
    {
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdfContent = "%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>
endobj
4 0 obj
<< /Length 135 >>
stream
BT
/F1 16 Tf
50 700 Td
(MINISTRY OF SPORT, RECREATION, ARTS AND CULTURE) Tj
/F1 12 Tf
0 -30 Td
(Official Application Supporting Documents Package) Tj
ET
endstream
endobj
5 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
xref
0 6
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000246 00000 n 
0000000432 00000 n 
trailer
<< /Size 6 /Root 1 0 R >>
startxref
513
%%EOF";

        file_put_contents($fullPath, $pdfContent);
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

    public function uploadOnboardingDocument(Request $request, int $id)
    {
        $application = Application::where('user_id', Auth::id())
            ->whereIn('status', [
                Application::STATUS_PLACEMENT_PENDING,
                Application::STATUS_APPROVED,
                Application::STATUS_PLACED,
            ])
            ->findOrFail($id);

        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($request->hasFile('document_file') && $request->file('document_file')->isValid()) {
            $path = $request->file('document_file')->store('onboarding_documents', 'public');

            ApplicationDocument::create([
                'application_id' => $application->id,
                'cert_name'      => $request->input('document_name'),
                'file_path'      => $path,
                'uploaded_at'    => now(),
                'is_verified'    => false,
            ]);

            ApplicationLog::log(
                $application->id,
                "Uploaded onboarding document: " . $request->input('document_name'),
                Auth::id()
            );

            return back()->with('success', 'Onboarding document (' . $request->input('document_name') . ') attached successfully.');
        }

        return back()->with('error', 'Failed to upload document. Please check the file and try again.');
    }
}
