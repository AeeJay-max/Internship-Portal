<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-1" style="color: #011C3E; font-family: 'Georgia', serif;">Reset Password</h2>
        <p class="text-sm text-gray-500">Choose a new strong password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email"
                       value="{{ old('email', $request->email) }}"
                       required autofocus
                       class="w-full pl-10 pr-4 py-3 border rounded-lg text-sm bg-white transition-all focus:outline-none @error('email') border-red-400 @else border-gray-300 @enderror"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
            </div>
            @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold mb-2" style="color: #011C3E;">New Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       placeholder="Minimum 8 characters"
                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm bg-white transition-all focus:outline-none"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                <button type="button" onclick="togglePass('password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Confirm New Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       placeholder="Repeat your new password"
                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm bg-white transition-all focus:outline-none"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                <button type="button" onclick="togglePass('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password_confirmation')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <button type="submit" id="btn-reset"
                class="w-full py-3 rounded-lg text-sm font-bold text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Reset Password
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
