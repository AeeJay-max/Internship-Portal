@extends('layouts.app')

@section('content')

    {{-- Government Portal Hero Section --}}
    <div class="relative overflow-hidden text-white min-h-[65vh] lg:min-h-[72vh] flex items-center py-14 sm:py-18 lg:py-22 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/branding/heroes_acre.jpg') }}'); background-position: center 30%;">
        
        {{-- Semi-Transparent MoSRAC Green Overlay (#02371B Gradient Preserved) --}}
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(0, 80, 38, 0.90) 0%, rgba(0, 60, 28, 0.88) 55%, rgba(10, 18, 12, 0.92) 100%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                {{-- LEFT SIDE: Text, Actions & Notice --}}
                <div class="lg:col-span-7 text-left space-y-5 sm:space-y-6">
                    
                    {{-- Institutional Label --}}
                    <div class="inline-flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-amber-400 rounded-full"></span>
                        <p class="text-xs sm:text-sm font-bold tracking-widest text-amber-300 uppercase">
                            MINISTRY OF SPORT, RECREATION, ARTS & CULTURE
                        </p>
                    </div>

                    {{-- Main Heading --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                        Internship Application Portal
                    </h1>

                    {{-- Supporting Text --}}
                    <p class="text-base sm:text-lg text-slate-100 font-normal leading-relaxed max-w-xl">
                        Apply for national internship opportunities and gain practical experience across the Ministry's departments in Zimbabwe.
                    </p>

                    @php
                        $userHasApplied = Auth::check() && !Auth::user()->isAdmin() && Auth::user()->applications()->exists();
                    @endphp

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-2">
                        @if(!$userHasApplied && (!Auth::check() || !Auth::user()->isAdmin()))
                            <a href="{{ route('apply.start') }}"
                               class="px-7 py-3.5 rounded-lg font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 transition-all text-sm text-center shadow-md hover:shadow-lg">
                                Apply for Internship
                            </a>
                        @endif

                        @guest
                            <a href="{{ route('login') }}"
                               class="px-7 py-3.5 rounded-lg font-semibold text-white bg-green-800 hover:bg-green-700 border border-white/35 hover:border-amber-300/80 transition-all text-sm text-center shadow-md">
                                Applicant Login
                            </a>
                        @else
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="px-7 py-3.5 rounded-lg font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 transition-all text-sm text-center shadow-md hover:shadow-lg">
                                    Go to Admin Control Panel
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                   class="px-7 py-3.5 rounded-lg font-bold text-slate-950 bg-amber-400 hover:bg-amber-300 transition-all text-sm text-center shadow-md hover:shadow-lg">
                                    Go to My Portal Dashboard
                                </a>
                            @endif
                        @endguest
                    </div>

                    {{-- Official Notice Line (PURE WHITE TEXT) --}}
                    <div class="pt-5 border-t border-white/20 text-xs text-white">
                        <p class="leading-relaxed text-white">
                            <strong class="text-white font-bold">Official Notice:</strong> General internship applications remain open continuously. You may submit an application to your preferred Ministry department at any time.
                        </p>
                    </div>

                </div>

                {{-- RIGHT SIDE: Large MOSRAC Ministry Logo (Flat on Green Overlay) --}}
                <div class="lg:col-span-5 flex justify-center lg:justify-end items-center pt-8 lg:pt-0">
                    <img src="{{ asset('images/branding/mosrac_logo.png') }}"
                         alt="Ministry of Sport, Recreation, Arts & Culture Official Logo"
                         class="w-64 h-64 sm:w-80 sm:h-80 md:w-96 md:h-96 lg:w-[420px] lg:h-[420px] max-w-full object-contain filter drop-shadow-lg"
                         onerror="this.onerror=null; this.src='{{ asset('images/branding/lionfalcon.png') }}';">
                </div>

            </div>
        </div>
    </div>

    {{-- Information Section Below Hero --}}
    <section class="py-12 lg:py-16 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="max-w-3xl mb-10">
                <div class="inline-flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#005A2B]"></span>
                    <span class="text-xs font-bold text-[#005A2B] uppercase tracking-wider">Ministry Portal Overview</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mb-2">
                    Internship Opportunities
                </h2>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                    Explore internship opportunities across departments within the Ministry of Sport, Recreation, Arts & Culture.
                </p>
            </div>

            {{-- Structured Information Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- Available Departments --}}
                <div class="bg-white border border-slate-200/90 rounded-lg p-6 sm:p-7 shadow-sm hover:shadow-md transition-shadow border-t-4 border-[#005A2B] flex flex-col justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 uppercase tracking-wide mb-1">
                            AVAILABLE DEPARTMENTS
                        </h3>
                        <p class="text-xs font-semibold text-amber-700 mb-3">
                            Explore internship opportunities across Ministry departments.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Structured attachments are offered across all 13 Ministry departments, including Sports And Recreation, Arts and Culture Promotion development, Information Communication Technology, and Human Resources.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('internships.index') }}" class="text-xs font-bold text-[#005A2B] hover:text-[#00421F] uppercase tracking-wider inline-flex items-center gap-1.5 transition-colors">
                            <span>View Opportunities</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Structured Placements --}}
                <div class="bg-white border border-slate-200/90 rounded-lg p-6 sm:p-7 shadow-sm hover:shadow-md transition-shadow border-t-4 border-[#005A2B] flex flex-col justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 uppercase tracking-wide mb-1">
                            STRUCTURED PLACEMENTS
                        </h3>
                        <p class="text-xs font-semibold text-amber-700 mb-3">
                            Gain practical workplace experience within the Ministry.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Designed for tertiary students seeking required industrial attachment or recent graduates aiming to build practical public sector work experience in Zimbabwe.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('about') }}" class="text-xs font-bold text-[#005A2B] hover:text-[#00421F] uppercase tracking-wider inline-flex items-center gap-1.5 transition-colors">
                            <span>Program Information</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Application Process --}}
                <div class="bg-white border border-slate-200/90 rounded-lg p-6 sm:p-7 shadow-sm hover:shadow-md transition-shadow border-t-4 border-[#005A2B] flex flex-col justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 uppercase tracking-wide mb-1">
                            APPLICATION PROCESS
                        </h3>
                        <p class="text-xs font-semibold text-amber-700 mb-3">
                            Submit your application and supporting documents through the portal.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Submit your personal information, academic transcripts, and institutional recommendation letters online for review by Ministry selection officers.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('how-to-apply') }}" class="text-xs font-bold text-[#005A2B] hover:text-[#00421F] uppercase tracking-wider inline-flex items-center gap-1.5 transition-colors">
                            <span>Application Guidelines</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection


