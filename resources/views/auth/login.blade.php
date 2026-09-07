<x-guest-layout>

    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 mb-1">
            Sign In to Portal
        </h2>
        <p class="text-xs text-slate-500 font-medium">Access your MoSRAC Internship Application Account</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-slate-800 mb-1.5">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="yourname@domain.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-800">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#005A2B] hover:text-[#00421F] transition">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="••••••••">
                <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center pt-1">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#005A2B] focus:ring-[#005A2B]">
            <label for="remember_me" class="ml-2 text-xs font-semibold text-slate-600">Keep me signed in on this device</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full py-3.5 px-4 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
            <span>Sign In to MoSRAC Portal</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        {{-- Register link --}}
        <p class="text-center text-xs text-slate-500 pt-3 border-t border-slate-100">
            New applicant?
            <a href="{{ route('register') }}" class="font-bold text-[#005A2B] hover:text-[#00421F] transition">
                Create Internship Account &rarr;
            </a>
        </p>

    </form>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input) input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-guest-layout>

