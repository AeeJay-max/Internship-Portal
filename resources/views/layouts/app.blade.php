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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

{{-- Global loading bar --}}
<div id="mosrac-loader" style="
    position: fixed; top: 0; left: 0; right: 0;
    height: 3px; z-index: 9999;
    background: linear-gradient(90deg, #011C3E, #0284c7, #16a34a);
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
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-white bg-blue-600">
                        M
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-base">Ministry of Sport, Recreation, Arts & Culture</h4>
                        <p class="text-xs text-slate-400">National Internship Application & Placement Portal</p>
                    </div>
                </div>
                <p class="text-sm text-slate-400 max-w-md">
                    Empowering Zimbabwean youth and tertiary students through professional attachments across Ministry departments in sports, recreation, arts, culture, ICT, administration, and public services.
                </p>
            </div>
            <div>
                <h5 class="text-white font-semibold text-sm mb-3 uppercase tracking-wider">Quick Links</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('apply.start') }}" class="hover:text-white transition">Apply for Internship</a></li>
                    <li><a href="{{ route('opportunities.index') }}" class="hover:text-white transition">Advertised Vacancies</a></li>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Applicant Dashboard</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-white transition">Ministry Announcements</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-semibold text-sm mb-3 uppercase tracking-wider">Contact Ministry</h5>
                <p class="text-sm text-slate-400">Headquarters Office</p>
                <p class="text-sm text-slate-400">Harare, Zimbabwe</p>
                <p class="text-sm text-slate-400 mt-2">Email: internships@mosrac.gov.zw</p>
                <p class="text-sm text-slate-400">Phone: +263 242 700100</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-8 pt-8 border-t border-slate-800 text-xs text-slate-500 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Ministry of Sport, Recreation, Arts & Culture. All rights reserved.</p>
            <p>Official Government Internship Portal</p>
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
