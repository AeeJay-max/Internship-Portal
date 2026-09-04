<x-guest-layout>

    <div class="mb-8">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"
             style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-3xl font-bold mb-2 text-center" style="color: #011C3E; font-family: 'Georgia', serif;">
            Verify Your Email
        </h2>
        <p class="text-gray-500 text-sm text-center leading-relaxed">
            Thanks for registering! Please check your inbox and click the verification link we sent you before continuing.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700 font-medium">A new verification link has been sent to your email address.</p>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" id="btn-resend"
                    class="w-full py-3.5 font-semibold text-sm uppercase tracking-wide text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full py-3 font-medium text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                ← Log Out and go back
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
