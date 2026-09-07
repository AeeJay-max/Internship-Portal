<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ministry of Sport, Recreation, Arts & Culture — Internship Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

{{-- Global loading bar --}}
<div id="mosrac-loader" style="
    position: fixed; top: 0; left: 0; right: 0;
    height: 3px; z-index: 9999;
    background: linear-gradient(90deg, #15803d, #f59e0b, #dc2626);
    background-size: 200% 100%;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
    display: none;
"></div>

<div class="min-h-screen flex flex-col justify-between">
    <div>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t-4 border-emerald-700 mt-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/mosrac_logo.png') }}" alt="MoSRAC Emblem" class="w-12 h-12 object-contain rounded-full border border-amber-300 bg-white p-0.5 shrink-0" onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                    <div>
                        <h4 class="text-white font-extrabold text-base">Ministry of Sport, Recreation, Arts & Culture</h4>
                        <p class="text-xs text-amber-300 font-semibold">MoSRAC Internship Application Portal</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 max-w-md leading-relaxed">
                    Official Government portal for structured national internship attachments and placements across Ministry departments in all 10 provinces of Zimbabwe.
                </p>
            </div>
            <div>
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Portal Navigation</h5>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('/') }}" class="hover:text-amber-300 transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-amber-300 transition">About MoSRAC</a></li>
                    <li><a href="{{ route('internships.index') }}" class="hover:text-amber-300 transition">Internship Opportunities</a></li>
                    <li><a href="{{ route('how-to-apply') }}" class="hover:text-amber-300 transition">How to Apply</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-300 transition">Contact Ministry</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Ministry Headquarters</h5>
                <p class="text-xs text-slate-300 font-semibold">Chinengundu Mashayamombe Building</p>
                <p class="text-xs text-slate-400">95 Cnr N. Mandela & S. V. Muzenda Street</p>
                <p class="text-xs text-slate-400">Harare, Zimbabwe</p>
                <p class="text-xs text-slate-400 mt-2">Email: minofsportandarts@gmail.com</p>
                <p class="text-xs text-slate-400">Phone: +263242708345</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-8 pt-8 border-t border-slate-800/80 text-[11px] text-slate-500 flex flex-col md:flex-row justify-between items-center gap-3">
            <p>&copy; {{ date('Y') }} Ministry of Sport, Recreation, Arts & Culture. All rights reserved.</p>
            <p class="text-slate-400">Government of Zimbabwe Official Portal</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@stack('scripts')

<script>
    (function() {
        var loader = document.getElementById('mosrac-loader');
        if (!loader) return;

        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form.method && form.method.toLowerCase() === 'get') return;
            loader.style.display = 'block';
            loader.style.transform = 'scaleX(0.7)';
        });

        document.addEventListener('click', function(e) {
            var link = e.target.closest('a');
            if (!link) return;
            if (link.href && !link.href.startsWith('#') &&
                !link.href.startsWith('javascript') &&
                link.target !== '_blank' &&
                link.hostname === window.location.hostname) {
                loader.style.display = 'block';
                loader.style.transform = 'scaleX(0.6)';
            }
        });

        window.addEventListener('pageshow', function() {
            loader.style.transform = 'scaleX(1)';
            setTimeout(function() {
                loader.style.display = 'none';
                loader.style.transform = 'scaleX(0)';
            }, 300);
        });
    })();
</script>

</body>
</html>
