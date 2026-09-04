<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function __construct()
    {
        // Only super admin can manage semesters
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Super Admin access required.');
        }
    }

    public function index()
    {
        $semesters = Semester::orderByDesc('starts_at')->get();
        return view('admin.portal.semesters.index', compact('semesters'));
    }

    public function create()
    {
        return view('admin.portal.semesters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'starts_at'     => 'required|date',
            'ends_at'       => 'required|date|after:starts_at',
        ]);

        Semester::create($request->only('name', 'academic_year', 'starts_at', 'ends_at'));

        return redirect()->route('admin.portal.semesters.index')
            ->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        return view('admin.portal.semesters.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'starts_at'     => 'required|date',
            'ends_at'       => 'required|date|after:starts_at',
        ]);

        $semester->update($request->only('name', 'academic_year', 'starts_at', 'ends_at'));

        return redirect()->route('admin.portal.semesters.index')
            ->with('success', 'Semester updated.');
    }

    public function setCurrent(Semester $semester)
    {
        // Only one semester can be current at a time
        Semester::query()->update(['is_current' => false]);
        $semester->update(['is_current' => true]);

        return back()->with('success', $semester->name . ' is now the current semester.');
    }
}
