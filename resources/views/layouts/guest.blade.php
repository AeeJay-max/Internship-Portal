<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MoSRAC — Internship Portal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-100">
<div class="min-h-screen flex">

    {{-- LEFT PANEL — Government Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col items-center justify-center bg-emerald-900 text-white">

        {{-- Zimbabwean Flag Top Accent Line --}}
        <div class="absolute top-0 left-0 right-0 h-2 flex">
            <div class="w-1/4 bg-emerald-700"></div>
            <div class="w-1/4 bg-amber-400"></div>
            <div class="w-1/4 bg-red-600"></div>
            <div class="w-1/4 bg-slate-950"></div>
        </div>

        {{-- Background radial pattern --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-800 via-emerald-900 to-slate-950 opacity-90"></div>

        {{-- Decorative Circles --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-emerald-700/20 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-amber-500/10 blur-2xl"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center px-12 max-w-lg">

            {{-- Coat of Arms / Emblem Badge --}}
            <div class="flex justify-center mb-6">
                <div class="w-28 h-28 rounded-full bg-white border-2 border-amber-300 flex items-center justify-center p-1.5 shadow-2xl">
                    <img src="{{ asset('images/branding/mosrac_logo.png') }}" alt="Ministry of Sport, Recreation, Arts and Culture Emblem" class="w-full h-full object-contain rounded-full" onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                </div>
            </div>

            <span class="inline-block px-3 py-1 bg-amber-400/20 text-amber-300 border border-amber-400/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                Government of Zimbabwe
            </span>

            <h1 class="text-white font-extrabold text-2xl lg:text-3xl leading-tight mb-2 tracking-tight">
                Ministry of Sport, Recreation,<br>Arts & Culture
            </h1>

            <p class="text-emerald-200 font-bold text-lg mb-6">
                MoSRAC Internship Portal
            </p>

            <div class="w-16 h-1 bg-amber-400 mx-auto rounded-full mb-6"></div>

            <p class="text-emerald-100/80 text-xs leading-relaxed max-w-sm mx-auto">
                Empowering Zimbabwean youth and tertiary students through practical national internship attachments across ministry departments.
            </p>

            {{-- Key Notice Highlights --}}
            <div class="mt-8 grid grid-cols-2 gap-3 text-left">
                <div class="p-3 bg-white/5 border border-white/10 rounded-xl">
                    <p class="text-amber-400 font-bold text-xs">Official Portal</p>
                    <p class="text-white/70 text-[11px]">Direct application submission to Head Office</p>
                </div>
                <div class="p-3 bg-white/5 border border-white/10 rounded-xl">
                    <p class="text-emerald-300 font-bold text-xs">1 PDF File</p>
                    <p class="text-white/70 text-[11px]">Merged 5-in-1 supporting documents</p>
                </div>
            </div>
        </div>

        {{-- Bottom link --}}
        <div class="absolute bottom-6 left-0 right-0 text-center">
            <a href="{{ url('/') }}" class="text-emerald-200/60 hover:text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Return to MoSRAC Portal Homepage</span>
            </a>
        </div>
    </div>

    {{-- RIGHT PANEL — Form Area --}}
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative bg-slate-50">

        {{-- Mobile Header --}}
        <div class="lg:hidden mb-6 text-center">
            <div class="w-16 h-16 rounded-xl bg-emerald-800 flex items-center justify-center mx-auto mb-2 shadow-md">
                <img src="{{ asset('images/branding/lionfalcon.png') }}" alt="Zimbabwe Emblem" class="w-10 h-10 object-contain">
            </div>
            <h2 class="font-extrabold text-slate-900 text-lg">MoSRAC Internship Portal</h2>
            <p class="text-xs text-slate-500">Ministry of Sport, Recreation, Arts & Culture</p>
        </div>

        <div class="w-full max-w-md bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-slate-500 text-center font-medium">
            © {{ date('Y') }} Ministry of Sport, Recreation, Arts & Culture. Government of Zimbabwe.
        </p>

    </div>

</div>
</body>
</html>

