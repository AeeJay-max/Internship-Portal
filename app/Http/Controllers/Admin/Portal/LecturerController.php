<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function __construct()
    {
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Super Admin access required.');
        }
    }

    public function index()
    {
        $lecturers = Lecturer::withCount('scheduleSlots')->orderBy('name')->get();
        return view('admin.portal.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        return view('admin.portal.lecturers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'title'   => 'required|string|max:50',
            'email'   => 'required|email|unique:lecturers,email',
            'faculty' => 'nullable|string|max:255',
        ]);

        Lecturer::create($request->only('name', 'title', 'email', 'faculty'));

        return redirect()->route('admin.portal.lecturers.index')
            ->with('success', 'Lecturer added successfully.');
    }

    public function edit(Lecturer $lecturer)
    {
        return view('admin.portal.lecturers.edit', compact('lecturer'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'title'   => 'required|string|max:50',
            'email'   => 'required|email|unique:lecturers,email,' . $lecturer->id,
            'faculty' => 'nullable|string|max:255',
        ]);

        $lecturer->update($request->only('name', 'title', 'email', 'faculty'));

        return redirect()->route('admin.portal.lecturers.index')
            ->with('success', 'Lecturer updated.');
    }

    public function destroy(Lecturer $lecturer)
    {
        $lecturer->delete();
        return back()->with('success', 'Lecturer removed.');
    }
}
