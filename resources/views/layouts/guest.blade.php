<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MOSRAC — National Internship Portal of Armenia</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">

    {{-- LEFT PANEL — Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col items-center justify-center"
         style="background: #011C3E;">

        {{-- Background image overlay --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-15"
             style="background-image: url('/images/hero/MOSRAC-hero.jpg');"></div>

        {{-- Animated geometric shapes --}}
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10"
             style="background: #611818; transform: translate(30%, -30%);"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full opacity-10"
             style="background: #611818; transform: translate(-30%, 30%);"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 rounded-full opacity-5"
             style="background: white; transform: translate(-50%, -50%);"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center px-12">

            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <div class="w-32 h-32 rounded-full flex items-center justify-center shadow-2xl"
                     style="background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                    <img src="/images/branding/MOSRAC.svg"
                         alt="MOSRAC Lion Falcon"
                         class="w-20 h-20 object-contain"
                         style="filter: brightness(0) invert(1);">
                </div>
            </div>

            <h1 class="text-white font-bold text-3xl leading-tight mb-3"
                style="font-family: 'Georgia', serif;">
                National Polytechnic<br>University of Armenia
            </h1>

            <div class="w-16 h-0.5 bg-white/40 mx-auto my-5"></div>

            <p class="text-white/70 text-sm leading-relaxed mb-10 max-w-xs mx-auto">
                Engineering Armenia's future through innovation, research, and academic excellence since 2018.
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 max-w-xs mx-auto">
                @foreach([['90+', 'Years'], ['12K+', 'Students'], ['200+', 'Programs']] as $s)
                    <div class="text-center p-3 rounded-lg" style="background: rgba(255,255,255,0.08);">
                        <div class="text-white font-bold text-xl" style="font-family: 'Georgia', serif;">{{ $s[0] }}</div>
                        <div class="text-white/50 text-xs mt-0.5">{{ $s[1] }}</div>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- Bottom link --}}
        <div class="absolute bottom-8 left-0 right-0 text-center">
            <a href="{{ url('/') }}" class="text-white/50 text-xs hover:text-white/80 transition-colors duration-200 flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to MOSRAC Website
            </a>
        </div>

    </div>

    {{-- RIGHT PANEL — Form --}}
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 relative"
         style="background: #f0f4f8;">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3"
                 style="background: #011C3E;">
                <img src="/images/branding/MOSRAC.svg" alt="MOSRAC" class="w-12 h-12 object-contain" style="filter: brightness(0) invert(1);">
            </div>
            <div class="font-bold text-lg" style="color: #011C3E;">MOSRAC</div>
            <div class="text-xs text-gray-500">Internship Portal</div>
        </div>

        <div class="w-full max-w-md">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-gray-400 text-center">
            © {{ date('Y') }} National Internship Portal of Armenia
        </p>

    </div>

</div>
</body>
</html>
