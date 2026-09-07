@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
    $hasApplied = Auth::check() && !Auth::user()->isAdmin() && Auth::user()->applications()->exists();
@endphp

<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 30 })"
     :class="scrolled ? 'shadow-xl' : ''"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     style="background: #15803d; border-bottom: 3px solid #f59e0b;">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20">

            {{-- Brand Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/branding/mosrac_logo.png') }}" alt="MoSRAC Official Emblem" class="w-12 h-12 object-contain rounded-full shadow-md bg-white p-0.5 border border-amber-300 shrink-0" onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                <div>
                    <div class="text-white font-black text-lg sm:text-xl leading-tight tracking-wider uppercase">
                        MoSRAC
                    </div>
                    <div class="text-[11px] leading-tight tracking-wider font-bold text-amber-300">
                        Internship Portal
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation Links --}}
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ url('/') }}"
                   class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->is('/') ? 'text-amber-300 border-amber-300' : 'text-white/90 hover:text-white border-transparent hover:border-white/50' }}">
                    Home
                </a>
                <a href="{{ route('about') }}"
                   class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('about') ? 'text-amber-300 border-amber-300' : 'text-white/90 hover:text-white border-transparent hover:border-white/50' }}">
                    About
                </a>

                @if(!$isAdmin && !$hasApplied)
                    <a href="{{ route('internships.index') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('internships.*') || request()->routeIs('opportunities.*') ? 'text-amber-300 border-amber-300' : 'text-white/90 hover:text-white border-transparent hover:border-white/50' }}">
                        Internship Opportunities
                    </a>
                    <a href="{{ route('how-to-apply') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('how-to-apply') ? 'text-amber-300 border-amber-300' : 'text-white/90 hover:text-white border-transparent hover:border-white/50' }}">
                        How to Apply
                    </a>
                @endif

                @if(!$isAdmin)
                    <a href="{{ route('contact') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('contact') ? 'text-amber-300 border-amber-300' : 'text-white/90 hover:text-white border-transparent hover:border-white/50' }}">
                        Contact
                    </a>
                @endif

                @auth
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-xs uppercase font-extrabold tracking-wide text-emerald-950 bg-amber-400 hover:bg-amber-300 px-3.5 py-1.5 rounded-lg shadow transition">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="text-xs uppercase font-extrabold tracking-wider text-white border-b-2 {{ request()->routeIs('dashboard') ? 'border-amber-300 text-amber-300' : 'border-transparent hover:border-white/50' }}">
                            My Portal
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Right Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    @if(!$isAdmin && !$hasApplied)
                        <a href="{{ route('apply.start') }}"
                           class="text-xs uppercase font-extrabold tracking-wider px-4 py-2 rounded-lg bg-amber-400 text-emerald-950 hover:bg-amber-300 transition-all duration-200 shadow-md">
                            Apply Now
                        </a>
                    @endif

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3.5 py-2 text-xs font-bold transition-colors rounded-lg text-white bg-emerald-800/80 hover:bg-emerald-800 border border-emerald-600/60">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0 bg-amber-400 text-emerald-950">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <svg class="w-3.5 h-3.5 text-amber-300 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if($isAdmin)
                                <x-dropdown-link :href="route('admin.dashboard')">Admin Control Panel</x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('dashboard')">My Internship Portal</x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('profile.edit')">Profile Settings</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-xs uppercase font-extrabold tracking-wider px-4 py-2 text-white hover:text-amber-300 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('apply.start') }}"
                       class="text-xs uppercase font-extrabold tracking-wider px-5 py-2.5 rounded-lg bg-amber-400 text-emerald-950 hover:bg-amber-300 transition-all duration-200 shadow-md">
                        Apply Now
                    </a>
                @endguest
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="lg:hidden flex flex-col gap-1.5 p-2 text-white focus:outline-none">
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>

        </div>
    </div>

    {{-- Mobile dropdown menu --}}
    <div x-show="open" x-cloak class="lg:hidden border-t border-emerald-700 bg-emerald-900">
        <div class="px-6 py-4 space-y-3">
            <a href="{{ url('/') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">Home</a>
            <a href="{{ route('about') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">About</a>

            @if(!$isAdmin && !$hasApplied)
                <a href="{{ route('internships.index') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">Internship Opportunities</a>
                <a href="{{ route('how-to-apply') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">How to Apply</a>
            @endif

            @if(!$isAdmin)
                <a href="{{ route('contact') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">Contact</a>
            @endif

            @auth
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-amber-300">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-white">My Portal</a>
                @endif
                <div class="pt-3 border-t border-emerald-700">
                    <div class="text-xs font-bold text-white mb-1">{{ Auth::user()->name }}</div>
                    <a href="{{ route('profile.edit') }}" class="block text-xs text-emerald-200 py-1">Profile Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block text-xs text-rose-300 font-bold py-1">Logout</button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="pt-3 border-t border-emerald-700 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="text-xs font-extrabold uppercase text-center py-2.5 border border-white/40 text-white rounded-lg">Login</a>
                    <a href="{{ route('apply.start') }}" class="text-xs font-extrabold uppercase text-center py-2.5 bg-amber-400 text-emerald-950 rounded-lg shadow">Apply Now</a>
                </div>
            @endguest
        </div>
    </div>

</nav>

<div class="h-20"></div>
