@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
    $user = Auth::user();
    $hasReachedMax = $user && !$isAdmin && $user->hasReachedMaxApplications();
    $hasApplied = $user && !$isAdmin && $user->applications()->exists();
@endphp

<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
     :class="scrolled ? 'shadow-lg bg-white/95 backdrop-blur-md' : 'bg-white'"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b-4 border-amber-400">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20">

            {{-- Brand Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/branding/mosrac_logo.png') }}" alt="MoSRAC Official Emblem" class="w-16 h-16 object-contain shrink-0 group-hover:scale-105 transition-transform" onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                <div>
                    <div class="text-[#005A2B] font-black text-lg sm:text-xl leading-tight tracking-wider uppercase">
                        MoSRAC
                    </div>
                    <div class="text-[11px] leading-tight tracking-wider font-extrabold text-amber-600">
                        Internship Portal
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation Links --}}
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ url('/') }}"
                   class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->is('/') ? 'text-[#005A2B] border-[#005A2B]' : 'text-slate-700 hover:text-[#005A2B] border-transparent hover:border-[#005A2B]/40' }}">
                    Home
                </a>
                <a href="{{ route('about') }}"
                   class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('about') ? 'text-[#005A2B] border-[#005A2B]' : 'text-slate-700 hover:text-[#005A2B] border-transparent hover:border-[#005A2B]/40' }}">
                    About
                </a>

                @if(!$isAdmin && !$hasApplied)
                    <a href="{{ route('internships.index') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('internships.*') || request()->routeIs('opportunities.*') ? 'text-[#005A2B] border-[#005A2B]' : 'text-slate-700 hover:text-[#005A2B] border-transparent hover:border-[#005A2B]/40' }}">
                        Internship Opportunities
                    </a>
                    <a href="{{ route('how-to-apply') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('how-to-apply') ? 'text-[#005A2B] border-[#005A2B]' : 'text-slate-700 hover:text-[#005A2B] border-transparent hover:border-[#005A2B]/40' }}">
                        How to Apply
                    </a>
                @endif

                @if(!$isAdmin)
                    <a href="{{ route('contact') }}"
                       class="text-xs uppercase font-extrabold tracking-wider transition-colors duration-200 py-1 border-b-2 {{ request()->routeIs('contact') ? 'text-[#005A2B] border-[#005A2B]' : 'text-slate-700 hover:text-[#005A2B] border-transparent hover:border-[#005A2B]/40' }}">
                        Contact
                    </a>
                @endif

                @auth
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-xs uppercase font-extrabold tracking-wide text-white bg-[#005A2B] hover:bg-[#00421F] px-4 py-2 rounded-xl shadow transition">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="text-xs uppercase font-extrabold tracking-wider text-[#005A2B] border-b-2 {{ request()->routeIs('dashboard') ? 'border-[#005A2B] text-[#005A2B]' : 'border-transparent hover:border-[#005A2B]/40' }}">
                            My Portal
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Right Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    @if(!$isAdmin)
                        @if($hasReachedMax)
                            <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                               class="text-xs uppercase font-extrabold tracking-wider px-5 py-2.5 rounded-xl bg-slate-300 text-slate-500 cursor-not-allowed shadow-none">
                                Apply Now
                            </button>
                        @else
                            <a href="{{ route('apply.start') }}"
                               class="text-xs uppercase font-extrabold tracking-wider px-5 py-2.5 rounded-xl bg-[#005A2B] text-white hover:bg-[#00421F] transition-all duration-200 shadow-md">
                                Apply Now
                            </a>
                        @endif
                    @endif

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3.5 py-2 text-xs font-bold transition-colors rounded-xl text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-300">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0 bg-[#005A2B] text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-500 opacity-80" fill="currentColor" viewBox="0 0 20 20">
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
                    <a href="{{ route('login') }}" class="text-xs uppercase font-extrabold tracking-wider px-4 py-2 text-[#005A2B] hover:text-[#00421F] transition-colors">
                        Login
                    </a>
                    <a href="{{ route('apply.start') }}"
                       class="text-xs uppercase font-extrabold tracking-wider px-5 py-2.5 rounded-xl bg-[#005A2B] text-white hover:bg-[#00421F] transition-all duration-200 shadow-md">
                        Apply Now
                    </a>
                @endguest
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="lg:hidden flex flex-col gap-1.5 p-2 text-[#005A2B] focus:outline-none">
                <span class="block w-6 h-0.5 bg-[#005A2B] transition-all duration-200" :class="open ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="block w-6 h-0.5 bg-[#005A2B] transition-all duration-200" :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-6 h-0.5 bg-[#005A2B] transition-all duration-200" :class="open ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>

        </div>
    </div>

    {{-- Mobile dropdown menu --}}
    <div x-show="open" x-cloak class="lg:hidden border-t border-slate-200 bg-white">
        <div class="px-6 py-4 space-y-3">
            <a href="{{ url('/') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-[#005A2B]">Home</a>
            <a href="{{ route('about') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-slate-800">About</a>

            @if(!$isAdmin && !$hasApplied)
                <a href="{{ route('internships.index') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-slate-800">Internship Opportunities</a>
                <a href="{{ route('how-to-apply') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-slate-800">How to Apply</a>
            @endif

            @if(!$isAdmin)
                <a href="{{ route('contact') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-slate-800">Contact</a>
            @endif

            @auth
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-[#005A2B]">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block text-xs uppercase font-extrabold tracking-wider py-2 text-[#005A2B]">My Portal</a>
                @endif
                <div class="pt-3 border-t border-slate-200">
                    <div class="text-xs font-bold text-slate-900 mb-1">{{ Auth::user()->name }}</div>
                    <a href="{{ route('profile.edit') }}" class="block text-xs text-slate-600 py-1">Profile Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block text-xs text-rose-600 font-bold py-1">Logout</button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="pt-3 border-t border-slate-200 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="text-xs font-extrabold uppercase text-center py-2.5 border border-[#005A2B] text-[#005A2B] rounded-xl">Login</a>
                    <a href="{{ route('apply.start') }}" class="text-xs font-extrabold uppercase text-center py-2.5 bg-[#005A2B] text-white rounded-xl shadow">Apply Now</a>
                </div>
            @endguest
        </div>
    </div>

</nav>

<div class="h-20"></div>
