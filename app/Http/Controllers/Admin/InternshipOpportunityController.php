<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\InternshipOpportunity;
use Illuminate\Http\Request;

class InternshipOpportunityController extends Controller
{
    public function index()
    {
        $opportunities = InternshipOpportunity::with('department')
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return view('admin.opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.opportunities.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id'   => 'required|exists:departments,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'requirements'    => 'nullable|string',
            'positions_count' => 'required|integer|min:1',
            'opening_date'    => 'nullable|date',
            'closing_date'    => 'nullable|date|after_or_equal:opening_date',
            'duration_months' => 'required|integer|min:1',
            'start_date'      => 'nullable|date',
            'status'          => 'required|in:draft,open,closed,filled,archived',
        ]);

        InternshipOpportunity::create($validated);

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Internship opportunity published successfully.');
    }

    public function edit(InternshipOpportunity $opportunity)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.opportunities.edit', compact('opportunity', 'departments'));
    }

    public function update(Request $request, InternshipOpportunity $opportunity)
    {
        $validated = $request->validate([
            'department_id'   => 'required|exists:departments,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'requirements'    => 'nullable|string',
            'positions_count' => 'required|integer|min:1',
            'opening_date'    => 'nullable|date',
            'closing_date'    => 'nullable|date',
            'duration_months' => 'required|integer|min:1',
            'start_date'      => 'nullable|date',
            'status'          => 'required|in:draft,open,closed,filled,archived',
        ]);

        $opportunity->update($validated);

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Internship opportunity updated successfully.');
    }

    public function destroy(InternshipOpportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Internship opportunity archived.');
    }
}
