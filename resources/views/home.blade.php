@extends('layouts.app')

@section('content')

    {{-- Government Portal Entry Hero Section with Heroes' Acre Background --}}
    <div class="relative overflow-hidden text-white min-h-[85vh] flex items-center justify-center py-24 px-4 sm:px-6 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/branding/heroes_acre.jpg') }}'); background-position: center 30%;">
        
        {{-- Semi-Transparent MoSRAC Green Overlay (#005A2B at ~0.78-0.84 opacity) --}}
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(0, 90, 43, 0.82) 0%, rgba(0, 66, 31, 0.84) 60%, rgba(11, 18, 12, 0.88) 100%);"></div>

        {{-- Subtle Zimbabwean Accent Ribbon & Geometry --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-25 z-[1]">
            <div class="absolute top-0 right-0 w-96 h-2.5 flex shadow-lg">
                <div class="h-full w-1/5 bg-[#005A2B]"></div>
                <div class="h-full w-1/5 bg-[#F59E0B]"></div>
                <div class="h-full w-1/5 bg-[#DC2626]"></div>
                <div class="h-full w-1/5 bg-black"></div>
                <div class="h-full w-1/5 bg-white"></div>
            </div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full border-[18px] border-amber-400/20 blur-sm"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center space-y-8 relative z-10">
            
            {{-- Government Official Emblem Badge --}}
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-slate-900/60 text-amber-300 border border-amber-400/40 text-xs font-black uppercase tracking-widest shadow-lg backdrop-blur-md">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                MoSRAC — Official Portal
            </div>

            {{-- Main Heading --}}
            <div class="space-y-4">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight drop-shadow-md">
                    Ministry of Sport, Recreation, Arts & Culture
                </h1>
                <div class="inline-block relative">
                    <p class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-amber-300 uppercase tracking-wide">
                        MoSRAC Internship Application Portal
                    </p>
                    <div class="w-24 h-1 bg-amber-400 mx-auto mt-2 rounded-full"></div>
                </div>
            </div>

            {{-- Short Description --}}
            <p class="text-base sm:text-lg text-slate-200 max-w-2xl mx-auto leading-relaxed font-medium">
                The official portal for tertiary students and graduates to apply for structured national internship attachments and placements across Ministry departments in Zimbabwe.
            </p>

            @php
                $userHasApplied = Auth::check() && !Auth::user()->isAdmin() && Auth::user()->applications()->exists();
            @endphp

            {{-- Primary Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                @if(!$userHasApplied && (!Auth::check() || !Auth::user()->isAdmin()))
                    <a href="{{ route('apply.start') }}"
                       class="w-full sm:w-auto px-9 py-4 rounded-xl font-extrabold text-slate-950 bg-amber-400 hover:bg-amber-300 border-2 border-amber-300 shadow-2xl hover:scale-[1.02] transition-all duration-200 text-sm uppercase tracking-wider flex items-center justify-center gap-2">
                        <span>Apply for Internship</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif

                @guest
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto px-9 py-4 rounded-xl font-extrabold text-white hover:text-amber-300 bg-slate-900/80 hover:bg-slate-900 border border-white/30 hover:border-amber-400 transition-all duration-200 text-sm uppercase tracking-wider text-center shadow-lg backdrop-blur-md">
                        Applicant Login
                    </a>
                @else
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="w-full sm:w-auto px-9 py-4 rounded-xl font-extrabold text-slate-950 bg-amber-400 hover:bg-amber-300 border-2 border-amber-300 transition-all duration-200 text-sm uppercase tracking-wider text-center shadow-2xl">
                            Go to Admin Control Panel
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto px-9 py-4 rounded-xl font-extrabold text-slate-950 bg-amber-400 hover:bg-amber-300 border-2 border-amber-300 transition-all duration-200 text-sm uppercase tracking-wider text-center shadow-2xl">
                            Go to My Portal Dashboard
                        </a>
                    @endif
                @endguest
            </div>

            {{-- Quick Portal Notice --}}
            <div class="pt-8 border-t border-white/10 max-w-xl mx-auto">
                <p class="text-xs text-slate-300 leading-normal">
                    📌 <strong>Official Notice:</strong> General internship applications remain open continuously. You may submit an application to your preferred Ministry department at any time.
                </p>
            </div>

        </div>
    </div>

@endsection
