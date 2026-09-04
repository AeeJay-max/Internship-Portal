<x-guest-layout>

    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-2" style="color: #011C3E; font-family: 'Georgia', serif;">
            Create Account
        </h2>
        <p class="text-gray-500 text-sm">Register to apply for MOSRAC programs</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name Fields --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-semibold mb-2" style="color: #011C3E;">First Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"
                           required autofocus
                           class="w-full pl-11 pr-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                           style="border-color: #d1dae6;"
                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                           placeholder="John">
                </div>
                @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"
                       required
                       class="w-full px-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="Doe">
                @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autocomplete="username"
                       class="w-full pl-11 pr-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="your@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       class="w-full pl-11 pr-12 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="Min. 8 characters">
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

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Confirm Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full pl-11 pr-12 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="Repeat your password">
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        {{-- Submit --}}
        <button type="submit" id="btn-register"
                class="w-full py-3.5 font-semibold text-sm uppercase tracking-wide text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 rounded-lg mt-2"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Create My Account
        </button>

        {{-- Login link --}}
        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}"
               class="font-semibold transition-colors duration-200"
               style="color: #611818;"
               onmouseover="this.style.color='#011C3E';"
               onmouseout="this.style.color='#611818';">
                Sign in →
            </a>
        </p>

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
            var form = document.getElementById('btn-register')?.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    var btn = document.getElementById('btn-register');
                    var valid = form.checkValidity();
                    if (valid) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="MOSRAC-spinner"></span>Creating Account...';
                        btn.style.opacity = '0.85';
                    }
                });
            }
        });
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-guest-layout>
