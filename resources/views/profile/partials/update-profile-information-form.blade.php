<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-5">
    @csrf
    @method('patch')

    {{-- Name — read only --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">First Name</label>
            <div class="w-full px-4 py-3 border rounded-lg text-sm bg-gray-50 text-gray-500 flex items-center justify-between"
                 style="border-color: #e5e7eb;">
                <span>{{ explode(' ', $user->name)[0] ?? '' }}</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Last Name</label>
            <div class="w-full px-4 py-3 border rounded-lg text-sm bg-gray-50 text-gray-500 flex items-center justify-between"
                 style="border-color: #e5e7eb;">
                <span>{{ implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '—' }}</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">Name cannot be changed. Contact admin if needed.</p>
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Email Address</label>

        @if($user->hasVerifiedEmail())
            {{-- Verified: show locked with change option --}}
            <div class="relative">
                <input id="email" name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center gap-1.5 pointer-events-none">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs text-green-600 font-medium">Verified</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">
                ⚠️ Changing your email will require re-verification. Enter your current password below to confirm the change.
            </p>

            {{-- Password required to change email --}}
            <div class="mt-3" id="email-change-password" style="display: none;">
                <label class="block text-xs font-semibold mb-1.5 text-gray-600">Current Password (required to change email)</label>
                <input type="password" name="email_change_password"
                       class="w-full px-4 py-2.5 border rounded-lg text-sm bg-white focus:outline-none"
                       style="border-color: #d1dae6;"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                       placeholder="Enter your password to confirm email change">
                <x-input-error class="mt-1" :messages="$errors->get('email_change_password')" />
            </div>

            <script>
                document.getElementById('email').addEventListener('input', function() {
                    const original = '{{ $user->email }}';
                    const passwordField = document.getElementById('email-change-password');
                    passwordField.style.display = this.value !== original ? 'block' : 'none';
                });
            </script>
        @else
            {{-- Not verified: freely editable --}}
            <input id="email" name="email" type="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   class="w-full px-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                   style="border-color: #d1dae6;"
                   onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                   onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
            <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-amber-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Not yet verified
                </span>
                <button form="send-verification"
                        class="text-xs font-semibold underline transition-colors"
                        style="color: #611818;">
                    Resend verification email
                </button>
            </div>
        @endif

        <x-input-error class="mt-1.5" :messages="$errors->get('email')" />
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-7 py-2.5 font-semibold text-sm text-white rounded-lg transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Save Changes
        </button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-sm font-medium text-green-600">
                ✓ Saved successfully
            </p>
        @endif
    </div>
</form>
