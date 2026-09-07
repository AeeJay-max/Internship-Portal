@extends('layouts.app')

@section('content')

    {{-- Minimal Government Portal Entry Point --}}
    <div class="relative bg-gradient-to-b from-emerald-950 via-slate-900 to-slate-950 text-white min-h-[75vh] flex items-center justify-center py-20 px-4 sm:px-6">
        
        {{-- Subtle Zimbabwean Flag Background Pattern --}}
        <div class="absolute inset-0 opacity-10 bg-cover bg-center" style="background-image: linear-gradient(135deg, #15803d 0%, #000000 50%, #15803d 100%);"></div>

        <div class="max-w-4xl mx-auto text-center space-y-8 relative z-10">
            
            {{-- Government Official Emblem Badge --}}
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-emerald-500/10 text-amber-300 border border-emerald-500/30 text-xs font-bold uppercase tracking-widest shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Republic of Zimbabwe — Official Portal
            </div>

            {{-- Main Heading --}}
            <div class="space-y-3">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight">
                    Ministry of Sport, Recreation, Arts & Culture
                </h1>
                <p class="text-xl sm:text-2xl font-bold text-emerald-400 uppercase tracking-wide">
                    MoSRAC Internship Application Portal
                </p>
            </div>

            {{-- Short Description --}}
            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                The official portal for tertiary students and graduates to apply for structured national internship attachments and placements across Ministry departments in Zimbabwe.
            </p>

            @php
                $userHasApplied = Auth::check() && !Auth::user()->isAdmin() && Auth::user()->applications()->exists();
            @endphp

            {{-- Primary Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                @if(!$userHasApplied && (!Auth::check() || !Auth::user()->isAdmin()))
                    <a href="{{ route('apply.start') }}"
                       class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-white bg-emerald-700 hover:bg-emerald-600 border border-emerald-500 shadow-xl hover:shadow-2xl transition-all duration-200 text-sm uppercase tracking-wider flex items-center justify-center gap-2">
                        <span>Apply for Internship</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif

                @guest
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-amber-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-amber-400/40 transition-all duration-200 text-sm uppercase tracking-wider text-center">
                        Applicant Login
                    </a>
                @else
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-slate-950 bg-amber-400 hover:bg-amber-300 border border-amber-300 transition-all duration-200 text-sm uppercase tracking-wider text-center">
                            Go to Admin Control Panel
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-slate-950 bg-amber-400 hover:bg-amber-300 border border-amber-300 transition-all duration-200 text-sm uppercase tracking-wider text-center">
                            Go to My Portal Dashboard
                        </a>
                    @endif
                @endguest
            </div>

            {{-- Quick Portal Notice --}}
            <div class="pt-6 border-t border-slate-800/80 max-w-xl mx-auto">
                <p class="text-xs text-slate-400 leading-normal">
                    General internship applications remain open continuously. You may submit an application to your preferred Ministry department at any time.
                </p>
            </div>

        </div>
    </div>

@endsection
