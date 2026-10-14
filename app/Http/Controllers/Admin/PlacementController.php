<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Department;
use App\Models\InternshipPlacement;
use App\Models\ApplicationLog;
use App\Notifications\ApplicationStatusUpdatedNotification;
use App\Notifications\SupervisorAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PlacementController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user', 'preference.preferredDepartment', 'placement.department'])
            ->whereIn('status', [
                Application::STATUS_APPROVED,
                Application::STATUS_PLACEMENT_PENDING,
                Application::STATUS_PLACED,
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.placements.index', compact('applications', 'departments'));
    }

    public function store(Request $request, int $applicationId)
    {
        $application = Application::whereIn('status', [
            Application::STATUS_APPROVED,
            Application::STATUS_PLACEMENT_PENDING,
            Application::STATUS_PLACED,
        ])->findOrFail($applicationId);

        $validated = $request->validate([
            'department_id'          => 'required|exists:departments,id',
            'division_unit'          => 'nullable|string|max:255',
            'placement_location'     => 'required|string|max:255',
            'supervisor_name'        => 'required|string|max:200',
            'supervisor_email'       => 'required|email|max:255',
            'supervisor_phone'       => 'nullable|string|max:50',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after:start_date',
            'duration'               => 'nullable|string|max:100',
            'placement_notes'        => 'nullable|string',
            'reporting_instructions' => 'required|string',
        ], [
            'supervisor_email.required' => 'Supervisor email address is required to dispatch the assignment notification.',
        ]);

        $placement = InternshipPlacement::updateOrCreate(
            ['application_id' => $application->id],
            $validated + [
                'application_id' => $application->id,
                'assigned_by'    => Auth::id(),
            ]
        );

        $application->update([
            'status' => Application::STATUS_PLACED,
        ]);

        $dept = Department::find($validated['department_id']);

        ApplicationLog::log(
            $application->id,
            "Assigned to {$dept->name} department under supervisor {$validated['supervisor_name']}.",
            Auth::id()
        );

        // 1. Send notification email to assigned supervisor
        if (!empty($validated['supervisor_email'])) {
            try {
                Notification::route('mail', $validated['supervisor_email'])
                    ->notify(new SupervisorAssignedNotification(
                        $placement,
                        $application->load(['personalInfo', 'academicInfo', 'user'])
                    ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending Supervisor Assigned notification: ' . $e->getMessage());
            }
        }

        // 2. Send notification email to applicant to check portal with immediate effect
        if ($application->user) {
            try {
                $startFormatted = \Carbon\Carbon::parse($validated['start_date'])->format('l, j F Y');
                $endFormatted   = \Carbon\Carbon::parse($validated['end_date'])->format('l, j F Y');
                $application->user->notify(new ApplicationStatusUpdatedNotification(
                    $application,
                    'Placed with Department',
                    "Congratulations! You have been successfully placed in the {$dept->name} department under supervisor {$validated['supervisor_name']}.\n\n" .
                    "Placement Period: {$startFormatted} to {$endFormatted}.\n" .
                    "Reporting Instructions: {$validated['reporting_instructions']}"
                ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending Placed notification to applicant: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Internship placement recorded and emails dispatched to supervisor and applicant successfully.');
    }
}
