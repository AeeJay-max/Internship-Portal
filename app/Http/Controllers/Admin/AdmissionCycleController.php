<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCycle;
use App\Models\Program;
use Illuminate\Http\Request;

class AdmissionCycleController extends Controller
{
    public function __construct()
    {
        // Only super admins can manage cycles
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admins can manage admission cycles.');
        }
    }

    public function index()
    {
        $cycles = AdmissionCycle::with('program')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.cycles.index', compact('cycles'));
    }

    public function create()
    {
        $programs = Program::where('is_active', true)->orderBy('name')->get();
        return view('admin.cycles.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'intake_name' => 'required|string|max:255',
            'starts_at'   => 'required|date',
            'deadline_at' => 'required|date|after:starts_at',
            'capacity'    => 'nullable|integer|min:1',
        ]);

        AdmissionCycle::create($validated);

        return redirect()->route('admin.cycles.index')
            ->with('success', 'Admission cycle created successfully.');
    }

    public function edit(AdmissionCycle $cycle)
    {
        $programs = Program::where('is_active', true)->orderBy('name')->get();
        return view('admin.cycles.edit', compact('cycle', 'programs'));
    }

    public function update(Request $request, AdmissionCycle $cycle)
    {
        $validated = $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'intake_name' => 'required|string|max:255',
            'starts_at'   => 'required|date',
            'deadline_at' => 'required|date|after:starts_at',
            'capacity'    => 'nullable|integer|min:1',
        ]);

        $cycle->update($validated);

        return redirect()->route('admin.cycles.index')
            ->with('success', 'Admission cycle updated successfully.');
    }

    public function destroy(AdmissionCycle $cycle)
    {
        // Only delete if no applications are linked
        if ($cycle->applications()->count() > 0) {
            return back()->with('error', 'Cannot delete a cycle that has applications linked to it.');
        }

        $cycle->delete();

        return redirect()->route('admin.cycles.index')
            ->with('success', 'Admission cycle deleted.');
    }
}
