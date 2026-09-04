<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-1" style="color: #011C3E; font-family: 'Georgia', serif;">Forgot Password?</h2>
        <p class="text-sm text-gray-500">No problem. Enter your email and we'll send you a reset link.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus
                       placeholder="your@email.com"
                       class="w-full pl-10 pr-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none @error('email') border-red-400 @else border-gray-300 @enderror"
                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                       onblur="this.style.borderColor='{{ $errors->has('email') ? '#f87171' : '#d1dae6' }}'; this.style.boxShadow='none';">
            </div>
            @error('email')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" id="btn-forgot"
                class="w-full py-3 rounded-lg text-sm font-bold text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Send Reset Link
        </button>

        <p class="text-center text-sm text-gray-500">
            Remember your password?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: #611818;">Back to Login</a>
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
