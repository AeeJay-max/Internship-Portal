<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $filter     = $request->get('filter', 'active');
        $search     = $request->get('search', '');
        $department = $request->get('department', '');

        $query = User::withTrashed()
            ->where('role', 'student')
            ->with(['applications' => function ($q) {
                $q->withTrashed()->with('preference.preferredDepartment')->latest('updated_at');
            }]);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($department) {
            $query->whereHas('applications.preference', fn($q) =>
                $q->where('preferred_department_id', $department)
            );
        }

        // Status filter
        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'draft') {
            $query->whereNull('deleted_at')
                ->whereHas('applications', fn($q) => $q->where('status', 'draft'));
        } elseif ($filter === 'inactive') {
            $query->whereNull('deleted_at')
                ->whereHas('applications', fn($q) => $q->where('status', 'draft')
                    ->where('updated_at', '<', now()->subDays(7)));
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }

        $users = $query->latest('created_at')->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'          => User::withTrashed()->where('role', 'student')->count(),
            'active'         => User::where('role', 'student')->count(),
            'deleted'        => User::onlyTrashed()->where('role', 'student')->count(),
            'with_draft'     => User::where('role', 'student')
                ->whereHas('applications', fn($q) => $q->where('status', 'draft'))
                ->count(),
            'inactive_draft' => User::where('role', 'student')
                ->whereHas('applications', fn($q) => $q->where('status', 'draft')
                    ->where('updated_at', '<', now()->subDays(7)))
                ->count(),
        ];

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.applicants.index', compact(
            'users', 'filter', 'search', 'stats', 'departments', 'department'
        ));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', '"' . $user->name . '" account restored successfully.');
    }
}
