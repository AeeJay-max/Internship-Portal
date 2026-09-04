<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    // Check at least admin access
    public function __construct()
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            abort(403, 'Access denied.');
        }
    }

    public function index()
    {
        $programs = Program::withTrashed()->with('admissionCycles')
            ->orderBy('degree_level')->orderBy('name')->paginate(20);
        $featured = Program::where('is_active', true)->where('is_featured', true)
            ->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.programs.index', compact('programs', 'featured'));
    }

    // Super admin only — add new program
    public function create()
    {
        $this->requireSuperAdmin();
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $this->requireSuperAdmin();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'faculty'      => 'nullable|string|max:255',
            'degree_level' => 'required|in:bachelor,master,phd',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order']  = $request->input('sort_order', 0);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image_path'] = $request->file('image')->store('programs', 'public');
        }

        Program::create($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program created successfully.');
    }

    // Both admin and super admin can edit
    public function edit(Program $program)
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        return view('admin.programs.edit', compact('program', 'isSuperAdmin'));
    }

    // Super admin — full update
    public function update(Request $request, Program $program)
    {
        $this->requireSuperAdmin();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'faculty'      => 'nullable|string|max:255',
            'degree_level' => 'required|in:bachelor,master,phd',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order']  = $request->input('sort_order', 0);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($program->image_path) Storage::disk('public')->delete($program->image_path);
            $validated['image_path'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    // Admin — can only update image, description, and featured status
    public function updateContent(Request $request, Program $program)
    {
        $request->validate([
            'description' => 'nullable|string',
        ]);

        $data = ['description' => $request->input('description')];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($program->image_path) Storage::disk('public')->delete($program->image_path);
            $data['image_path'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')
            ->with('success', '"' . $program->name . '" updated successfully.');
    }

    // Both admin and super admin can toggle featured
    public function toggleFeatured(Program $program)
    {
        $program->update(['is_featured' => !$program->is_featured]);
        $msg = $program->fresh()->is_featured ? 'added to' : 'removed from';
        return back()->with('success', '"' . $program->name . '" ' . $msg . ' home slider.');
    }

    // Super admin only
    public function destroy(Program $program)
    {
        $this->requireSuperAdmin();
        if ($program->admissionCycles()->count() > 0) {
            return back()->with('error', 'Cannot delete a program that has admission cycles. Deactivate it instead.');
        }
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }

    public function restore($id)
    {
        $this->requireSuperAdmin();
        Program::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.programs.index')->with('success', 'Program restored.');
    }

    private function requireSuperAdmin()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admins can perform this action.');
        }
    }
}
