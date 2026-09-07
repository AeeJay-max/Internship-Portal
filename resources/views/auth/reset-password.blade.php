<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 mb-1">Reset Password</h2>
        <p class="text-xs text-slate-500 font-medium">Choose a new strong password for your MoSRAC account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-xs font-bold text-slate-800 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email"
                       value="{{ old('email', $request->email) }}"
                       required autofocus
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
            </div>
            @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-800 mb-1.5">New Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       placeholder="Minimum 8 characters"
                       class="w-full pl-10 pr-12 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
                <button type="button" onclick="togglePass('password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-800 mb-1.5">Confirm New Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       placeholder="Repeat your new password"
                       class="w-full pl-10 pr-12 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
                <button type="button" onclick="togglePass('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password_confirmation')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <button type="submit" id="btn-reset"
                class="w-full py-3.5 px-4 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
            <span>Reset Password</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>


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
            var form = document.getElementById('btn-reset')?.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    var btn = document.getElementById('btn-reset');
                    if (form.checkValidity()) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="MOSRAC-spinner"></span>Resetting...';
                        btn.style.opacity = '0.85';
                    }
                });
            }
        });
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-guest-layout>
