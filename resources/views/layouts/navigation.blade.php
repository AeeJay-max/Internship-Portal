<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 })"
     :class="scrolled ? 'shadow-lg' : ''"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     style="background: #011C3E;">

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-20 md:h-24">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-xl text-white shadow-inner" style="background: linear-gradient(135deg, #1e3a8a, #0284c7);">
                    M
                </div>
                <div>
                    <div class="text-white font-bold text-base leading-tight tracking-wide">Ministry of Sport, Recreation, Arts & Culture</div>
                    <div class="text-xs leading-tight tracking-wider font-medium text-blue-300">National Internship Portal</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}"
                   class="text-sm font-semibold tracking-wide transition-colors duration-200 pb-0.5 border-b-2 {{ request()->is('/') ? 'text-white border-white' : 'text-white/85 hover:text-white border-transparent hover:border-white/50' }}">
                    Home
                </a>
                <a href="{{ route('opportunities.index') }}"
                   class="text-sm font-semibold tracking-wide transition-colors duration-200 pb-0.5 border-b-2 hover:border-white/50 text-white/85 hover:text-white {{ request()->routeIs('opportunities.*') ? 'border-white text-white' : 'border-transparent' }}">
                    Internship Opportunities
                </a>
                <a href="{{ route('news.index') }}"
                   class="text-sm font-semibold tracking-wide transition-colors duration-200 pb-0.5 border-b-2 hover:border-white/50 text-white/85 hover:text-white {{ request()->routeIs('news.*') ? 'border-white text-white' : 'border-transparent' }}">
                    Ministry News
                </a>
                <a href="{{ route('about') }}"
                   class="text-sm font-semibold tracking-wide transition-colors duration-200 pb-0.5 border-b-2 hover:border-white/50 text-white/85 hover:text-white {{ request()->routeIs('about') ? 'border-white' : 'border-transparent' }}">
                    About Ministry
                </a>

                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm font-semibold tracking-wide text-amber-300 hover:text-amber-200 transition-colors duration-200 bg-amber-900/40 px-3 py-1.5 rounded-lg border border-amber-500/30">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="text-sm font-semibold tracking-wide transition-colors duration-200 pb-0.5 border-b-2 hover:border-white/50 text-white/85 hover:text-white {{ request()->routeIs('dashboard') ? 'border-white text-white' : 'border-transparent' }}">
                            My Internship Portal
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Right Actions --}}
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-lg text-white bg-white/10 hover:bg-white/20">
                                <div class="w-7 h-7 rounded-full overflow-hidden flex items-center justify-center text-xs font-bold shrink-0 bg-blue-800 text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="text-xs px-2 py-0.5 rounded font-semibold bg-amber-400/20 text-amber-300">
                                        Admin
                                    </span>
                                @endif
                                <svg class="w-4 h-4 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">My Internship Portal</x-dropdown-link>
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
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white/80 hover:text-white transition-colors">
                        Applicant Login
                    </a>
                    <a href="{{ route('apply.start') }}"
                       class="text-sm font-bold px-5 py-2.5 rounded-lg tracking-wide uppercase transition-all duration-200 hover:bg-blue-50 bg-white text-slate-900 shadow-md">
                        Apply for Internship
                    </a>
                @endguest
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="md:hidden flex flex-col gap-1.5 p-2">
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-200" :class="open ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>

        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" class="md:hidden border-t bg-[#011C3E] border-white/10">
        <div class="px-6 py-4 space-y-3">
            <a href="{{ url('/') }}" class="block text-sm font-medium py-2 text-white/90">Home</a>
            <a href="{{ route('opportunities.index') }}" class="block text-sm font-medium py-2 text-white/90">Opportunities</a>
            <a href="{{ route('news.index') }}" class="block text-sm font-medium py-2 text-white/90">News</a>
            <a href="{{ route('about') }}" class="block text-sm font-medium py-2 text-white/90">About Ministry</a>

            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block text-sm font-semibold py-2 text-amber-300">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block text-sm font-medium py-2 text-white/90">My Internship Portal</a>
                @endif
                <div class="pt-3 border-t border-white/10">
                    <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                    <a href="{{ route('profile.edit') }}" class="block text-xs text-white/60 py-1">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block text-xs text-red-300 py-1">Logout</button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="pt-3 border-t border-white/10 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-center py-2.5 border border-white/30 text-white rounded-lg">Applicant Login</a>
                    <a href="{{ route('apply.start') }}" class="text-sm font-bold text-center py-2.5 bg-white text-slate-900 rounded-lg uppercase">Apply for Internship</a>
                </div>
            @endguest
        </div>
    </div>

</nav>

<div class="h-20 md:h-24"></div>
