@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Admin Accounts</h1>
                            <p class="text-gray-500 text-sm mt-1">Manage administrator access to the portal</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.users.create') }}"
                               class="px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                               style="background: #011C3E;">
                                + Add Admin
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
                    @endif

                    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Joined</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($admins as $admin)
                                <tr class="hover:bg-gray-50 transition {{ $admin->id === Auth::id() ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                                 style="background: {{ $admin->isSuperAdmin() ? '#011C3E' : '#611818' }};">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800">
                                                    {{ $admin->name }}
                                                    @if($admin->id === Auth::id())
                                                        <span class="text-xs text-blue-500 font-normal ml-1">(you)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $admin->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($admin->isSuperAdmin())
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Super Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Admin</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $admin->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        @if($admin->id !== Auth::id())
                                            <div class="flex items-center gap-3" x-data="{ showReset: false }">
                                                {{-- Reset Password --}}
                                                <button @click="showReset = !showReset"
                                                        class="text-xs font-medium text-gray-500 hover:text-blue-600 transition">
                                                    Reset Password
                                                </button>

                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('admin.users.destroy', $admin) }}"
                                                      onsubmit="return confirm('Remove {{ $admin->name }} as admin? This cannot be undone.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 transition">
                                                        Remove
                                                    </button>
                                                </form>

                                                {{-- Inline reset password form --}}
                                                <div x-show="showReset" x-transition
                                                     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                                                    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md mx-4" @click.stop>
                                                        <h3 class="font-bold text-gray-800 mb-1">Reset Password</h3>
                                                        <p class="text-sm text-gray-500 mb-5">Set a new password for <strong>{{ $admin->name }}</strong></p>
                                                        <form method="POST" action="{{ route('admin.users.resetPassword', $admin) }}">
                                                            @csrf
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <label class="block text-xs font-semibold mb-1 text-gray-600">New Password</label>
                                                                    <input type="password" name="password" required minlength="8"
                                                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                                                                           onfocus="this.style.borderColor='#611818';"
                                                                           onblur="this.style.borderColor='#d1d5db';">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-xs font-semibold mb-1 text-gray-600">Confirm Password</label>
                                                                    <input type="password" name="password_confirmation" required
                                                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none"
                                                                           onfocus="this.style.borderColor='#611818';"
                                                                           onblur="this.style.borderColor='#d1d5db';">
                                                                </div>
                                                            </div>
                                                            <div class="flex justify-end gap-3 mt-6">
                                                                <button type="button" @click="showReset = false"
                                                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                        class="px-4 py-2 text-sm font-semibold text-white rounded-lg"
                                                                        style="background: #011C3E;">
                                                                    Reset Password
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
