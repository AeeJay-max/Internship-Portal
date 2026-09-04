<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#011C3E">
    <title>MOSRAC Student Portal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .portal-gradient { background: linear-gradient(135deg, #011627 0%, #011C3E 60%, #611818 100%); }
        .card { background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; }
        .card-hover { transition: all 0.2s; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(2,62,138,0.10); }
        .bottom-nav-item { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 8px 4px; font-size: 11px; font-weight: 600; transition: color 0.15s; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="font-sans antialiased" style="background: #f0f4f8;">

{{-- ── TOP NAV (desktop) ───────────────────────────────────── --}}
<nav class="portal-gradient no-print" style="box-shadow: 0 2px 16px rgba(0,0,0,0.25);">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="flex items-center h-14 gap-4">

            {{-- Logo --}}
            <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(255,255,255,0.15);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    </svg>
                </div>
                <div class="hidden sm:block">
                    <div class="text-white font-bold text-xs leading-none">MOSRAC</div>
                    <div class="text-xs leading-none mt-0.5" style="color: #90caf9; font-size: 10px;">Student Portal</div>
                </div>
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">
                @foreach([
                    ['portal.dashboard',  'Dashboard',  'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['portal.schedule',   'Schedule',   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['portal.grades',     'Grades',     'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['portal.transcript', 'Transcript', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ] as [$route, $label, $icon])
                    <a href="{{ route($route) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all
                               {{ request()->routeIs($route) ? 'bg-white/20 text-white' : 'text-white/65 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Right --}}
            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ url('/') }}"
                   class="hidden md:flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border text-white/60 hover:text-white hover:bg-white/10 transition"
                   style="border-color: rgba(255,255,255,0.2);">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Home
                </a>
                <a href="{{ route('dashboard') }}"
                   class="hidden md:flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border text-white/60 hover:text-white hover:bg-white/10 transition"
                   style="border-color: rgba(255,255,255,0.2);">
                    Applications
                </a>
                @php $s = Auth::user()->student; @endphp
                <div class="hidden lg:block text-right border-l border-white/20 pl-3">
                    <div class="text-white text-xs font-semibold leading-none">{{ Auth::user()->name }}</div>
                    <div class="font-mono mt-0.5" style="font-size: 10px; color: #90caf9;">{{ $s?->student_number }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-8 h-8 rounded-lg flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 transition" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Flash --}}
@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 pt-3 no-print">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    </div>
@endif

{{-- Page content — extra bottom padding on mobile for bottom nav --}}
<main class="max-w-7xl mx-auto px-4 py-5 md:px-6 md:py-8 pb-24 md:pb-8">
    @yield('content')
</main>

{{-- ── BOTTOM NAV (mobile only) ─────────────────────────────── --}}
<nav class="fixed bottom-0 left-0 right-0 md:hidden no-print border-t"
     style="background: white; border-color: #e2e8f0; box-shadow: 0 -4px 20px rgba(0,0,0,0.08); z-index: 50; padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center">
        @foreach([
            ['portal.dashboard',  'Home',       'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['portal.schedule',   'Schedule',   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['portal.grades',     'Grades',     'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['portal.transcript', 'Transcript', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['dashboard',         'Exit',       'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'],
        ] as [$route, $label, $icon])
            @php $active = request()->routeIs($route); @endphp
            <a href="{{ route($route) }}" class="bottom-nav-item"
               style="color: {{ $active ? '#011C3E' : '#94a3b8' }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $active ? '2.5' : '1.8' }}" d="{{ $icon }}"/>
                </svg>
                {{ $label }}
            </a>
        @endforeach
    </div>
</nav>

@stack('scripts')
</body>
</html>
