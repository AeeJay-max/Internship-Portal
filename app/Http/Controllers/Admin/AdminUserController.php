<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admins can manage admin accounts.');
        }
    }

    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('role')
            ->orderBy('name')
            ->get();
        return view('admin.users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,super_admin',
        ]);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'],
            'email_verified_at' => now(), // admins don't need email verification
            'is_active'         => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account created successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting other super admins
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot delete a Super Admin.');
        }

        // Make sure at least one super admin remains
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Cannot delete the last Super Admin account.');
            }
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account removed.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password reset successfully for ' . $user->name . '.');
    }
}
