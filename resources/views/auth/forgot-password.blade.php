<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 mb-1">Forgot Password?</h2>
        <p class="text-xs text-slate-500 font-medium">No problem. Enter your registered email address and we'll send you a password reset link.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#005A2B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-[#005A2B] font-medium">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold text-slate-800 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus
                       placeholder="yourname@domain.com"
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20">
            </div>
            @error('email')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" id="btn-forgot"
                class="w-full py-3.5 px-4 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
            <span>Email Password Reset Link</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        <p class="text-center text-xs text-slate-500 pt-3 border-t border-slate-100">
            Remember your password?
            <a href="{{ route('login') }}" class="font-bold text-[#005A2B] hover:text-[#00421F] transition">Back to Login &rarr;</a>
        </p>
    </form>
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        .MOSRAC-spinner {
            display: inline-block; width: 13px; height: 13px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: white; border-radius: 50%;
            animation: spin 0.65s linear infinite;
            vertical-align: middle; margin-right: 5px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('btn-forgot')?.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    var btn = document.getElementById('btn-forgot');
                    if (form.checkValidity()) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="MOSRAC-spinner"></span>Sending...';
                        btn.style.opacity = '0.85';
                    }
                });
            }
        });
    </script>
</x-guest-layout>
