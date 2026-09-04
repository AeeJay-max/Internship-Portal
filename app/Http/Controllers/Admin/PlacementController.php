<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Department;
use App\Models\InternshipPlacement;
use App\Models\ApplicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'supervisor_email'       => 'nullable|email|max:255',
            'supervisor_phone'       => 'nullable|string|max:50',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after:start_date',
            'duration'               => 'nullable|string|max:100',
            'placement_notes'        => 'nullable|string',
            'reporting_instructions' => 'required|string',
        ]);

        InternshipPlacement::updateOrCreate(
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

        return back()->with('success', 'Internship placement recorded successfully.');
    }
}
