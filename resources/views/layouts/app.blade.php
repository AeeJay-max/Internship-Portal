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

{{-- Global Toast Notification Container --}}
<div id="mosrac-toast-container" class="fixed top-24 right-5 z-[9999] flex flex-col gap-3 max-w-md w-full pointer-events-none px-4 sm:px-0"></div>

{{-- Global loading bar --}}
<div id="mosrac-loader" style="
    position: fixed; top: 0; left: 0; right: 0;
    height: 3px; z-index: 9999;
    background: linear-gradient(90deg, #005A2B, #f59e0b, #dc2626);
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
    <footer class="bg-[#0B120C] text-slate-400 py-14 border-t-4 border-[#005A2B] mt-20 relative">
        {{-- Zimbabwean Flag Top Thin Accent Line --}}
        <div class="absolute top-0 left-0 right-0 h-1 flex">
            <div class="h-full w-1/5 bg-[#005A2B]"></div>
            <div class="h-full w-1/5 bg-[#F59E0B]"></div>
            <div class="h-full w-1/5 bg-[#DC2626]"></div>
            <div class="h-full w-1/5 bg-black"></div>
            <div class="h-full w-1/5 bg-white"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/mosrac_logo.png') }}" alt="MoSRAC Emblem" class="w-16 h-16 object-contain shrink-0" onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                    <div>
                        <h4 class="text-white font-extrabold text-base">Ministry of Sport, Recreation, Arts & Culture</h4>
                        <p class="text-xs text-amber-400 font-bold uppercase tracking-wider">MoSRAC Internship Application Portal</p>
                    </div>
                </div>
                <p class="text-xs text-slate-300 max-w-md leading-relaxed">
                    Official Government portal for structured national internship attachments and placements across Ministry departments in all 10 provinces of Zimbabwe.
                </p>
            </div>
            <div>
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Portal Navigation
                </h5>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('/') }}" class="hover:text-amber-300 transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-amber-300 transition">About MoSRAC</a></li>
                    <li><a href="{{ route('internships.index') }}" class="hover:text-amber-300 transition">Internship Opportunities</a></li>
                    <li><a href="{{ route('how-to-apply') }}" class="hover:text-amber-300 transition">How to Apply</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-300 transition">Contact Ministry</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Ministry Headquarters
                </h5>
                <p class="text-xs text-slate-200 font-semibold">Chinengundu Mashayamombe Building</p>
                <p class="text-xs text-slate-300">95 Cnr N. Mandela & S. V. Muzenda Street</p>
                <p class="text-xs text-slate-300">Harare, Zimbabwe</p>
                <p class="text-xs text-slate-300 mt-2">Email: minofsportandarts@gmail.com</p>
                <p class="text-xs text-slate-300">Phone: +263242708345</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-10 pt-6 border-t border-slate-800 text-[11px] text-slate-400 flex flex-col md:flex-row justify-between items-center gap-3">
            <p>&copy; {{ date('Y') }} Ministry of Sport, Recreation, Arts & Culture. All rights reserved.</p>
            <p class="text-amber-400 font-bold">Government of Zimbabwe Official Portal</p>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@stack('scripts')

<script>
    function showToast(message, type = 'warning') {
        var container = document.getElementById('mosrac-toast-container');
        if (!container) return;

        var toast = document.createElement('div');
        toast.className = 'pointer-events-auto flex items-center justify-between p-4 rounded-2xl shadow-2xl border text-xs font-bold transition-all duration-300 transform translate-y-2 opacity-0 ' +
            (type === 'error' || type === 'warning'
                ? 'bg-amber-900 text-amber-100 border-amber-500/60 shadow-amber-900/30'
                : type === 'danger'
                ? 'bg-rose-900 text-rose-100 border-rose-500/60 shadow-rose-900/30'
                : 'bg-emerald-900 text-emerald-100 border-emerald-500/60 shadow-emerald-900/30');

        toast.innerHTML = '<div class="flex items-center gap-3">' +
            '<span class="text-base">' + (type === 'danger' ? '⛔' : type === 'warning' || type === 'error' ? '⚠️' : '✅') + '</span>' +
            '<span>' + message + '</span>' +
            '</div>' +
            '<button onclick="this.parentElement.remove()" class="ml-4 text-white/70 hover:text-white font-black text-sm">&times;</button>';

        container.appendChild(toast);

        requestAnimationFrame(function() {
            toast.classList.remove('translate-y-2', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        setTimeout(function() {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
            setTimeout(function() { toast.remove(); }, 300);
        }, 5000);
    }

    @if(session('toast_warning') || session('toast_error'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('toast_warning') ?? session('toast_error') }}", 'warning');
        });
    @elseif(session('toast_success'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('toast_success') }}", 'success');
        });
    @endif

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
