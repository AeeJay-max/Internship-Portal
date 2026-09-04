@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">
                    <div class="max-w-xl mx-auto">

                        <div class="flex items-center gap-4 mb-8">
                            <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                            <h1 class="text-2xl font-bold text-blue-900">Add Admin Account</h1>
                        </div>

                        <form method="POST" action="{{ route('admin.users.store') }}"
                              class="bg-white rounded-xl shadow p-8 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       placeholder="e.g. Admissions Officer"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="admin@MOSRAC.am"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Role *</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="role" value="admin" class="sr-only" id="role_admin"
                                            @checked(old('role', 'admin') === 'admin')>
                                        <div class="border-2 rounded-lg px-4 py-3 text-sm font-semibold transition-all cursor-pointer h-full"
                                             id="label_admin" onclick="selectRole('admin')">
                                            <div>Admin</div>
                                            <p class="text-xs font-normal text-gray-400 mt-1">Review applications, manage news & program content</p>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="role" value="super_admin" class="sr-only" id="role_super_admin"
                                            @checked(old('role') === 'super_admin')>
                                        <div class="border-2 rounded-lg px-4 py-3 text-sm font-semibold transition-all cursor-pointer h-full"
                                             id="label_super_admin" onclick="selectRole('super_admin')">
                                            <div>Super Admin</div>
                                            <p class="text-xs font-normal text-gray-400 mt-1">Full access including programs, cycles & admin accounts</p>
                                        </div>
                                    </label>
                                </div>
                                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Password *</label>
                                <input type="password" name="password" required minlength="8"
                                       placeholder="Minimum 8 characters"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required
                                       placeholder="Repeat password"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.users.index') }}"
                                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit" id="btn-create-admin"
                                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                    Create Admin Account
                                </button>
                            </div>
                        </form>
                    </div>


                    <style>
                        @keyframes spin { to { transform: rotate(360deg); } }
                        .MOSRAC-spinner {
                            display: inline-block;
                            width: 13px; height: 13px;
                            border: 2px solid rgba(255,255,255,0.35);
                            border-top-color: white;
                            border-radius: 50%;
                            animation: spin 0.65s linear infinite;
                            vertical-align: middle;
                            margin-right: 5px;
                        }
                    </style>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var btn = document.getElementById('btn-create-admin');
                            if (btn) {
                                btn.closest('form').addEventListener('submit', function() {
                                    if (this.checkValidity()) {
                                        btn.disabled = true;
                                        btn.innerHTML = '<span class="MOSRAC-spinner"></span>Creating...';
                                        btn.style.opacity = '0.85';
                                    }
                                });
                            }
                        });
                        function selectRole(val) {
                            ['admin', 'super_admin'].forEach(v => {
                                const label = document.getElementById('label_' + v);
                                const input = document.getElementById('role_' + v);
                                if (v === val) {
                                    input.checked = true;
                                    label.style.background = '#011C3E';
                                    label.style.borderColor = '#011C3E';
                                    label.style.color = 'white';
                                } else {
                                    input.checked = false;
                                    label.style.background = '';
                                    label.style.borderColor = '#e5e7eb';
                                    label.style.color = '#374151';
                                }
                            });
                        }
                        selectRole('{{ old('role', 'admin') }}');
                    </script>

                </div>{{-- end centered form --}}
            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
