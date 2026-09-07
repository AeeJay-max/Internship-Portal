<x-guest-layout>

    <div class="mb-6 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-[#005A2B] text-white shadow-md">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 mb-1">
            Verify Your Email
        </h2>
        <p class="text-xs text-slate-500 font-medium leading-relaxed">
            Thanks for registering! Please check your inbox and click the verification link we sent you before continuing.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#005A2B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-[#005A2B] font-medium">A new verification link has been sent to your email address.</p>
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" id="btn-resend"
                    class="w-full py-3.5 px-4 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                <span>Resend Verification Email</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full py-2.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                ← Log Out and return to homepage
            </button>
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
            var btn = document.getElementById('btn-resend');
            if (btn) {
                btn.closest('form').addEventListener('submit', function() {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="MOSRAC-spinner"></span>Sending...';
                    btn.style.opacity = '0.85';
                });
            }
        });
    </script>
</x-guest-layout>
