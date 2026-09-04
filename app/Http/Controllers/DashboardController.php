<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\InternshipOpportunity;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $draft = Application::where('user_id', $user->id)
            ->where('status', Application::STATUS_DRAFT)
            ->with(['preference.preferredDepartment'])
            ->latest()
            ->first();

        $applications = Application::where('user_id', $user->id)
            ->where('status', '!=', Application::STATUS_DRAFT)
            ->with(['preference.preferredDepartment', 'placement.department', 'opportunity.department'])
            ->latest()
            ->get();

        $latestApplication = $applications->first() ?? $draft;

        $openOpportunities = InternshipOpportunity::with('department')->where('status', 'open')->latest()->limit(3)->get();

        return view('dashboard', [
            'draft'             => $draft,
            'applications'      => $applications,
            'latestApplication' => $latestApplication,
            'openOpportunities' => $openOpportunities,
        ]);
    }
}
