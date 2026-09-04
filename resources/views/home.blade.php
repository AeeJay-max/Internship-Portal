@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<div class="relative bg-slate-900 text-white overflow-hidden py-20 lg:py-28">
    <div class="absolute inset-0 opacity-20 bg-cover bg-center" style="background-image: linear-gradient(135deg, #011C3E 0%, #0f172a 100%);"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="max-w-3xl space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 text-xs font-semibold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Official Government Portal
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                Ministry of Sport, Recreation, Arts & Culture
            </h1>

            <p class="text-xl sm:text-2xl font-light text-blue-200">
                National Internship & Academic Placement Portal
            </p>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                Apply for an internship with the Ministry of Sport, Recreation, Arts & Culture and gain practical experience in your chosen field.
            </p>

            {{-- ZERO VACANCY EXPLANATION BANNER --}}
            <div class="p-4 rounded-xl bg-blue-900/40 border border-blue-400/30 flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-slate-200">
                    <strong class="text-white font-semibold block mb-0.5">General Applications Always Open:</strong>
                    You can submit a general internship application even when no specific internship opportunity is currently advertised. Your application will enter the Ministry placement pool for evaluation by administrators.
                </div>
            </div>

            {{-- Call to Action Buttons --}}
            <div class="flex flex-wrap items-center gap-4 pt-4">
                <a href="{{ route('apply.start') }}"
                   class="px-8 py-4 rounded-xl font-bold text-slate-900 bg-white hover:bg-blue-50 shadow-lg hover:shadow-xl transition-all duration-200 text-base uppercase tracking-wider">
                    Apply for Internship
                </a>
                <a href="{{ route('opportunities.index') }}"
                   class="px-7 py-4 rounded-xl font-semibold text-white bg-blue-700/60 hover:bg-blue-700 border border-blue-500/40 transition-all duration-200 text-base">
                    View Opportunities
                </a>
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-4 rounded-xl font-semibold text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 transition-all duration-200 text-base">
                    Track Application
                </a>
                @guest
                    <a href="{{ route('login') }}"
                       class="px-6 py-4 text-base font-semibold text-blue-300 hover:text-white transition-colors">
                        Applicant Login &rarr;
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

{{-- Quick Statistics --}}
<div class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div>
            <div class="text-3xl lg:text-4xl font-extrabold text-blue-900">14</div>
            <div class="text-sm text-slate-600 font-medium mt-1">Ministry Departments</div>
        </div>
        <div>
            <div class="text-3xl lg:text-4xl font-extrabold text-blue-900">100%</div>
            <div class="text-sm text-slate-600 font-medium mt-1">Digital Processing</div>
        </div>
        <div>
            <div class="text-3xl lg:text-4xl font-extrabold text-blue-900">10</div>
            <div class="text-sm text-slate-600 font-medium mt-1">Provinces Covered</div>
        </div>
        <div>
            <div class="text-3xl lg:text-4xl font-extrabold text-blue-900">24/7</div>
            <div class="text-sm text-slate-600 font-medium mt-1">Application Tracking</div>
        </div>
    </div>
</div>

{{-- Ministry Departments Overview --}}
<div class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Ministry Departments & Internship Domains
            </h2>
            <p class="text-slate-600 text-base">
                Select your preferred Ministry department during the application process. Placements are offered across technical, administrative, legal, and specialized sectors.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($departments as $dept)
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center font-bold text-lg">
                            {{ $dept->code }}
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $dept->name }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ Str::limit($dept->description, 120) }}
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-100 text-slate-700">
                            Capacity: {{ $dept->capacity ?? 'Open' }}
                        </span>
                        <a href="{{ route('apply.start') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900">
                            Apply to Department &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 text-slate-500">
                    Departments loading...
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Advertised Opportunities Section --}}
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Advertised Internship Opportunities</h2>
                <p class="text-slate-600 mt-2">Browse specific vacancies advertised by Ministry administrators.</p>
            </div>
            <a href="{{ route('opportunities.index') }}" class="font-bold text-blue-700 hover:text-blue-900 text-sm">
                View All Opportunities &rarr;
            </a>
        </div>

        @if($openOpportunities->isEmpty())
            <div class="p-8 rounded-2xl bg-amber-50 border border-amber-200 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mx-auto text-xl font-bold">
                    0
                </div>
                <h3 class="text-lg font-bold text-amber-900">0 Advertised Vacancies Available</h3>
                <p class="text-sm text-amber-800 max-w-xl mx-auto">
                    There are currently zero advertised vacancies. However, you can STILL click <strong>"Apply for Internship"</strong> below to submit a general internship application.
                </p>
                <a href="{{ route('apply.start') }}" class="inline-block px-6 py-3 rounded-xl bg-blue-900 text-white font-bold text-sm shadow hover:bg-blue-800 transition">
                    Apply for General Internship
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($openOpportunities as $opp)
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-100 text-blue-800 uppercase">
                                {{ $opp->department->name ?? 'Ministry' }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-900">{{ $opp->title }}</h3>
                            <p class="text-xs text-slate-500">Duration: {{ $opp->duration_months }} months | Positions: {{ $opp->positions_count }}</p>
                            <p class="text-sm text-slate-600">{{ Str::limit($opp->description, 100) }}</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <a href="{{ route('opportunities.show', $opp->id) }}" class="inline-block w-full text-center py-2.5 rounded-lg bg-blue-900 text-white font-semibold text-xs hover:bg-blue-800 transition">
                                View Details & Apply
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Ministry News --}}
@if(!$news->isEmpty())
    <div class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-12">Latest Ministry Announcements</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($news as $item)
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="text-xs font-semibold text-slate-400">{{ $item->published_at ? $item->published_at->format('d M Y') : 'Recent' }}</span>
                            <h3 class="text-base font-bold text-slate-900">{{ $item->title }}</h3>
                            <p class="text-sm text-slate-600">{{ Str::limit($item->excerpt, 100) }}</p>
                        </div>
                        <a href="{{ route('news.show', $item->slug) }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 mt-4 inline-block">
                            Read Announcement &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection
