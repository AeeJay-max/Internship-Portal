<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Department;
use App\Models\InternshipOpportunity;
use App\Models\InternshipPlacement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications'  => Application::count(),
            'draft'               => Application::where('status', Application::STATUS_DRAFT)->count(),
            'submitted'           => Application::where('status', Application::STATUS_SUBMITTED)->count(),
            'under_review'        => Application::where('status', Application::STATUS_UNDER_REVIEW)->count(),
            'documents_requested' => Application::where('status', Application::STATUS_DOCUMENTS_REQUESTED)->count(),
            'shortlisted'         => Application::where('status', Application::STATUS_SHORTLISTED)->count(),
            'interview_required'  => Application::where('status', Application::STATUS_INTERVIEW_REQUIRED)->count(),
            'approved'            => Application::where('status', Application::STATUS_APPROVED)->count(),
            'placement_pending'   => Application::where('status', Application::STATUS_PLACEMENT_PENDING)->count(),
            'placed'              => Application::where('status', Application::STATUS_PLACED)->count(),
            'rejected'            => Application::where('status', Application::STATUS_REJECTED)->count(),
            'total_departments'   => Department::count(),
            'total_opportunities' => InternshipOpportunity::count(),
        ];

        // Applications by preferred department
        $byDepartment = Department::withCount('preferences')
            ->orderBy('preferences_count', 'desc')
            ->get();

        // Placements by department
        $placementsByDepartment = Department::withCount('placements')
            ->orderBy('placements_count', 'desc')
            ->get();

        // Monthly submissions for last 6 months
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->copy()->subMonths($i);
            $monthlyData->push([
                'label' => $month->format('M Y'),
                'count' => Application::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ]);
        }

        $recentApplications = Application::with(['user', 'preference.preferredDepartment'])
            ->whereNotIn('status', [Application::STATUS_DRAFT])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentApplications',
            'byDepartment',
            'placementsByDepartment',
            'monthlyData'
        ));
    }
}
