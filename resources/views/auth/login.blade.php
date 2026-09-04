<x-guest-layout>

    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-2" style="color: #011C3E; font-family: 'Georgia', serif;">
            Welcome Back
        </h2>
        <p class="text-gray-500 text-sm">Sign in to your MOSRAC admissions account</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold mb-2" style="color: #011C3E;">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="w-full pl-11 pr-4 py-3 border rounded-lg text-sm transition-all duration-200 bg-white focus:outline-none focus:ring-2"
                       style="border-color: #d1dae6; focus-ring-color: #611818;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="your@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-semibold" style="color: #011C3E;">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-medium transition-colors duration-200"
                       style="color: #611818;"
                       onmouseover="this.style.color='#011C3E';"
                       onmouseout="this.style.color='#611818';">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="w-full pl-11 pr-12 py-3 border rounded-lg text-sm transition-all duration-200 bg-white focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="••••••••">
                {{-- Toggle password --}}
                <button type="button" onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Keep me signed in</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3.5 font-semibold text-sm uppercase tracking-wide text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 rounded-lg"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Sign In to Portal
        </button>

        {{-- Register link --}}
        <p class="text-center text-sm text-gray-500">
            New applicant?
            <a href="{{ route('register') }}"
               class="font-semibold transition-colors duration-200"
               style="color: #611818;"
               onmouseover="this.style.color='#011C3E';"
               onmouseout="this.style.color='#611818';">
                Create an account →
            </a>
        </p>

    </form>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-guest-layout>
