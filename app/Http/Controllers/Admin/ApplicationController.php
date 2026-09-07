<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationLog;
use App\Models\DocumentType;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationRejected;
use App\Notifications\ApplicationUnderReview;
use App\Notifications\DocumentsRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user', 'preference.preferredDepartment', 'opportunity.department'])
            ->whereIn('status', [
                Application::STATUS_SUBMITTED,
                Application::STATUS_UNDER_REVIEW,
                Application::STATUS_DOCUMENTS_REQUESTED,
                Application::STATUS_SHORTLISTED,
                Application::STATUS_INTERVIEW_REQUIRED,
                Application::STATUS_APPROVED,
                Application::STATUS_PLACEMENT_PENDING,
                Application::STATUS_PLACED,
                Application::STATUS_REJECTED,
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('preference', fn($q) => $q->where('preferred_department_id', $request->department_id));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total'               => Application::whereNotIn('status', [Application::STATUS_DRAFT])->count(),
            'submitted'           => Application::where('status', Application::STATUS_SUBMITTED)->count(),
            'under_review'        => Application::where('status', Application::STATUS_UNDER_REVIEW)->count(),
            'documents_requested' => Application::where('status', Application::STATUS_DOCUMENTS_REQUESTED)->count(),
            'shortlisted'         => Application::where('status', Application::STATUS_SHORTLISTED)->count(),
            'interview_required'  => Application::where('status', Application::STATUS_INTERVIEW_REQUIRED)->count(),
            'approved'            => Application::where('status', Application::STATUS_APPROVED)->count(),
            'placement_pending'   => Application::where('status', Application::STATUS_PLACEMENT_PENDING)->count(),
            'placed'              => Application::where('status', Application::STATUS_PLACED)->count(),
            'rejected'            => Application::where('status', Application::STATUS_REJECTED)->count(),
        ];

        $resubmitted = Application::with(['user', 'preference.preferredDepartment'])
            ->where('status', Application::STATUS_SUBMITTED)
            ->where('needs_reupload_review', true)
            ->latest()
            ->get();

        return view('admin.applications.index', compact('applications', 'stats', 'resubmitted'));
    }

    public function show(int $id)
    {
        $application = Application::with([
            'user',
            'personalInfo',
            'academicInfo',
            'preference.preferredDepartment',
            'preference.secondPreferredDepartment',
            'opportunity.department',
            'documents.documentType',
            'placement.department',
            'logs.performer',
        ])->findOrFail($id);

        if ($application->needs_reupload_review) {
            $application->update(['needs_reupload_review' => false]);
        }

        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.applications.show', compact('application', 'departments'));
    }

    public function markUnderReview(int $id)
    {
        $application = Application::findOrFail($id);

        if ($application->status === Application::STATUS_UNDER_REVIEW) {
            return back()->with('info', 'Application is already under review.');
        }

        $application->update([
            'status'                => Application::STATUS_UNDER_REVIEW,
            'flagged_document_ids'  => null,
            'needs_reupload_review' => false,
            'reviewed_by'           => Auth::id(),
            'reviewed_at'           => now(),
        ]);

        ApplicationLog::log(
            $application->id,
            'Application marked as Under Review.',
            Auth::id()
        );

        if ($application->user) {
            try {
                $application->user->notify(new ApplicationUnderReview($application));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending Under Review notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Application marked as Under Review.');
    }

    public function requestDocuments(Request $request, int $id)
    {
        $request->validate([
            'flagged_document_ids'   => 'required|array',
            'flagged_document_ids.*' => 'integer|exists:document_types,id',
            'review_notes'           => 'nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($id);

        if ($application->status === Application::STATUS_DOCUMENTS_REQUESTED) {
            return back()->with('info', 'Additional documents have already been requested.');
        }

        $application->update([
            'status'               => Application::STATUS_DOCUMENTS_REQUESTED,
            'reviewed_by'          => Auth::id(),
            'flagged_document_ids' => $request->flagged_document_ids,
            'review_notes'         => $request->review_notes ?: null,
        ]);

        $docNames = DocumentType::whereIn('id', $request->flagged_document_ids)->pluck('name')->implode(', ');

        ApplicationLog::log(
            $application->id,
            'Admin requested document re-upload for: ' . $docNames,
            Auth::id()
        );

        if ($application->user) {
            try {
                $application->user->notify(new DocumentsRequested($application));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending Documents Requested notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Applicant requested to submit additional documents.');
    }

    public function shortlist(int $id)
    {
        $application = Application::findOrFail($id);

        if ($application->status === Application::STATUS_SHORTLISTED) {
            return back()->with('info', 'Applicant is already shortlisted.');
        }

        $application->update([
            'status'      => Application::STATUS_SHORTLISTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        ApplicationLog::log($application->id, 'Applicant shortlisted for Ministry internship.', Auth::id());

        return back()->with('success', 'Applicant shortlisted successfully.');
    }

    public function requestInterview(Request $request, int $id)
    {
        $application = Application::findOrFail($id);

        if ($application->status === Application::STATUS_INTERVIEW_REQUIRED) {
            return back()->with('info', 'Interview has already been requested for this application.');
        }

        $application->update([
            'status'       => Application::STATUS_INTERVIEW_REQUIRED,
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->input('notes', 'Interview required by Ministry admin.'),
        ]);

        ApplicationLog::log($application->id, 'Interview requested by administrator.', Auth::id());

        return back()->with('success', 'Status updated to Interview Required.');
    }

    public function approve(Request $request, int $id)
    {
        $application = Application::findOrFail($id);

        if (in_array($application->status, [Application::STATUS_PLACEMENT_PENDING, Application::STATUS_APPROVED, Application::STATUS_PLACED])) {
            return back()->with('info', 'Application is already approved (Placement Pending / Placed).');
        }

        $requiredDocs = $request->input('required_docs', []);
        $customNotes  = $request->input('review_notes');

        $notesParts = [];
        if (!empty($requiredDocs)) {
            $notesParts[] = "REQUIRED ONBOARDING DOCUMENTS TO BRING / ATTACH:\n• " . implode("\n• ", $requiredDocs);
        }
        if ($customNotes) {
            $notesParts[] = "ACCEPTOR INSTRUCTIONS:\n" . $customNotes;
        }

        $formattedNotes = implode("\n\n", $notesParts);

        DB::transaction(function () use ($application, $formattedNotes) {
            $application->update([
                'status'       => Application::STATUS_PLACEMENT_PENDING,
                'reviewed_by'  => Auth::id(),
                'reviewed_at'  => now(),
                'review_notes' => $formattedNotes ?: 'Application approved by Ministry. Onboarding pending.',
            ]);

            ApplicationLog::log(
                $application->id,
                "Application approved by Ministry. Status moved to Placement Pending." . ($formattedNotes ? " Required docs specified." : ""),
                Auth::id()
            );

            if ($application->user) {
                try {
                    $application->user->notify(new ApplicationApproved($application));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed sending Application Approved notification: ' . $e->getMessage());
                }
            }
        });

        return back()->with('success', 'Application approved! Status is now Placement Pending with onboarding requirements recorded.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'review_notes' => 'required|string|min:5|max:1000',
        ]);

        $application = Application::findOrFail($id);

        if ($application->status === Application::STATUS_REJECTED) {
            return back()->with('info', 'Application is already rejected.');
        }

        $application->update([
            'status'       => Application::STATUS_REJECTED,
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        ApplicationLog::log(
            $application->id,
            'Application rejected. Reason: ' . $request->review_notes,
            Auth::id()
        );

        if ($application->user) {
            try {
                $application->user->notify(new ApplicationRejected($application));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending Application Rejected notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Application rejected.');
    }

    public function viewDocument(int $applicationId, int $documentId)
    {
        $doc = \App\Models\ApplicationDocument::where('application_id', $applicationId)->findOrFail($documentId);
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $filePath = $doc->file_path;

        if (!$filePath || !$disk->exists($filePath)) {
            $filePath = 'documents/test_placeholder.pdf';
            if (!$disk->exists($filePath)) {
                $this->ensurePlaceholderPdfExists($disk->path($filePath));
            }
        }

        $fullPath = $disk->path($filePath);
        $filename = \Illuminate\Support\Str::slug($doc->application->reference_number ?: 'MoSRAC_Application') . '_Supporting_Documents.pdf';

        $response = response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
        ]);
        $response->setContentDisposition(\Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE, $filename);

        return $response;
    }

    public function downloadDocument(int $applicationId, int $documentId, \Illuminate\Http\Request $request)
    {
        $doc = \App\Models\ApplicationDocument::where('application_id', $applicationId)->findOrFail($documentId);
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $filePath = $doc->file_path;

        if (!$filePath || !$disk->exists($filePath)) {
            $filePath = 'documents/test_placeholder.pdf';
            if (!$disk->exists($filePath)) {
                $this->ensurePlaceholderPdfExists($disk->path($filePath));
            }
        }

        $fullPath = $disk->path($filePath);
        $filename = \Illuminate\Support\Str::slug($doc->application->reference_number ?: 'MoSRAC_Application') . '_Supporting_Documents.pdf';

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
}
