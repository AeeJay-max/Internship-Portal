<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-5">
    @csrf
    @method('patch')

    {{-- Name — read only --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-800 mb-1.5">First Name</label>
            <div class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs bg-slate-50 text-slate-500 flex items-center justify-between">
                <span>{{ explode(' ', $user->name)[0] ?? '' }}</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-800 mb-1.5">Last Name</label>
            <div class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs bg-slate-50 text-slate-500 flex items-center justify-between">
                <span>{{ implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '—' }}</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Name cannot be changed. Contact admin if needed.</p>
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-xs font-bold text-slate-800 mb-1.5">Email Address</label>

        @if($user->hasVerifiedEmail())
            {{-- Verified: show locked with change option --}}
            <div class="relative">
                <input id="email" name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center gap-1.5 pointer-events-none">
                    <svg class="w-4 h-4 text-[#005A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs text-[#005A2B] font-bold">Verified</span>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">
                ⚠️ Changing your email will require re-verification. Enter your current password below to confirm the change.
            </p>

            {{-- Password required to change email --}}
            <div class="mt-3" id="email-change-password" style="display: none;">
                <label class="block text-xs font-semibold mb-1.5 text-slate-600">Current Password (required to change email)</label>
                <input type="password" name="email_change_password"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
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
                   class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
            <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-amber-600 flex items-center gap-1 font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Not yet verified
                </span>
                <button form="send-verification"
                        class="text-xs font-bold text-[#005A2B] hover:text-[#00421F] underline transition-colors">
                    Resend verification email
                </button>
            </div>
        @endif

        <x-input-error class="mt-1.5" :messages="$errors->get('email')" />
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-6 py-3 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all">
            Save Changes
        </button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-xs font-bold text-[#005A2B]">
                ✓ Saved successfully
            </p>
        @endif
    </div>
</form>
