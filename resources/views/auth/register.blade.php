<x-guest-layout>

    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 mb-1">
            Create Account
        </h2>
        <p class="text-xs text-slate-500 font-medium">Register for MoSRAC Internship Application Portal</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name Fields --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="block text-xs font-bold text-slate-800 mb-1">First Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"
                           required autofocus
                           class="w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                           placeholder="First Name">
                </div>
                @error('first_name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-xs font-bold text-slate-800 mb-1">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"
                       required
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="Surname">
                @error('last_name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-slate-800 mb-1">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autocomplete="username"
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="yourname@domain.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-bold text-slate-800 mb-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="Min. 8 characters">
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

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-800 mb-1">Confirm Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl text-xs transition bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                       placeholder="Repeat your password">
                <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        {{-- Submit --}}
        <button type="submit" id="btn-register" class="w-full py-3.5 px-4 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-3">
            <span>Create Applicant Account</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        {{-- Login link --}}
        <p class="text-center text-xs text-slate-500 pt-3 border-t border-slate-100">
            Already have an account?
            <a href="{{ route('login') }}" class="font-bold text-[#005A2B] hover:text-[#00421F] transition">
                Sign in &rarr;
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

