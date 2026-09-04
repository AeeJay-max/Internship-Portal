<?php

namespace App\Http\Controllers;

use App\Models\InternshipOpportunity;
use App\Models\Department;
use Illuminate\Http\Request;

class PublicOpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = InternshipOpportunity::with('department')->where('status', 'open');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%");
            });
        }

        $opportunities = $query->latest()->paginate(12)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('opportunities.index', compact('opportunities', 'departments'));
    }

    public function show(int $id)
    {
        $opportunity = InternshipOpportunity::with('department')->where('status', 'open')->findOrFail($id);

        return view('opportunities.show', compact('opportunity'));
    }
}
