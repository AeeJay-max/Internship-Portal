@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <div class="relative overflow-hidden text-white min-h-[45vh] lg:min-h-[50vh] flex items-center py-12 sm:py-16 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/branding/heroes_acre.jpg') }}'); background-position: center 30%;">
        
        {{-- Semi-Transparent MoSRAC Green Overlay --}}
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(0, 80, 38, 0.90) 0%, rgba(0, 60, 28, 0.88) 55%, rgba(10, 18, 12, 0.92) 100%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="max-w-2xl text-left space-y-4">
                
                {{-- Institutional Label --}}
                <div class="inline-flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-amber-400 rounded-full"></span>
                    <p class="text-xs sm:text-sm font-bold tracking-widest text-amber-300 uppercase">
                        MINISTRY PLACEMENTS & VACANCIES
                    </p>
                </div>

                {{-- Main Heading --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                    Internship Opportunities
                </h1>

                {{-- Supporting Description --}}
                <p class="text-base sm:text-lg text-white font-normal leading-relaxed max-w-xl">
                    Browse specific internship positions opened by Ministry departments or submit a general application.
                </p>

            </div>
        </div>
    </div>

    {{-- Main Opportunities Container --}}
    <section class="py-12 lg:py-16 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Search & Department Filter Bar --}}
            <div class="bg-white border border-slate-200/90 rounded-md p-5 sm:p-6 shadow-sm border-t-4 border-[#005A2B]">
                <h2 class="text-xs font-bold text-[#005A2B] uppercase tracking-wider mb-3">
                    SEARCH INTERNSHIP OPPORTUNITIES
                </h2>
                <form method="GET" action="{{ route('internships.index') }}" class="flex flex-col md:flex-row gap-3 items-center">
                    <div class="flex-1 w-full">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search position or keyword..."
                               class="w-full rounded-md border-slate-300 text-xs sm:text-sm px-3.5 py-2.5 focus:ring-[#005A2B] focus:border-[#005A2B]">
                    </div>
                    <div class="w-full md:w-72">
                        <select name="department_id" class="w-full rounded-md border-slate-300 text-xs sm:text-sm px-3.5 py-2.5 focus:ring-[#005A2B] focus:border-[#005A2B]">
                            <option value="">All Ministry Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-7 py-2.5 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider transition-all shadow-sm">
                        Filter Positions
                    </button>
                </form>
            </div>

            @php
                $user = Auth::user();
                $isAdmin = $user && $user->isAdmin();
                $hasReachedMax = $user && !$isAdmin && $user->hasReachedMaxApplications();
                $hasGeneralApp = $user && !$isAdmin && $user->hasGeneralApplication();
                $hasOppApp = $user && !$isAdmin && $user->hasOpportunityApplication();
                $appliedDeptId = $user && !$isAdmin ? $user->getAppliedDepartmentId() : null;
                $appliedDeptName = $user && !$isAdmin ? ($user->getAppliedDepartment()?->name ?? 'your department') : '';
            @endphp

            {{-- General Application Notice --}}
            <div class="bg-amber-50/80 border-l-4 border-l-amber-500 border-t border-r border-b border-amber-200/80 rounded-r-md p-6 sm:p-7 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1.5">
                    <span class="text-xs font-bold text-amber-900 uppercase tracking-wider block">GENERAL APPLICATION</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Submit General Internship Application</h2>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed max-w-2xl">
                        No suitable position listed? Applicants may submit a general internship application to their preferred Ministry department at any time.
                    </p>
                </div>
                <div>
                    @if($hasReachedMax)
                        <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                                class="px-6 py-3 rounded-md bg-slate-300 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-not-allowed shadow-none shrink-0 whitespace-nowrap">
                            General Application (Max Reached)
                        </button>
                    @elseif($hasGeneralApp)
                        <button type="button" onclick="showToast('You have already submitted a general internship application.', 'warning')"
                                class="px-6 py-3 rounded-md bg-slate-300 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-not-allowed shadow-none shrink-0 whitespace-nowrap">
                            General Application Submitted
                        </button>
                    @elseif(!$isAdmin)
                        <a href="{{ route('application.selectType') }}" class="px-6 py-3 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md transition-all inline-block shrink-0 whitespace-nowrap">
                            Submit General Application &rarr;
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md transition-all inline-block shrink-0 whitespace-nowrap">
                            Go to Portal Dashboard &rarr;
                        </a>
                    @endif
                </div>
            </div>

            {{-- Internship Opportunities Listing --}}
            @if($opportunities->isEmpty())
                <div class="bg-white border border-slate-200/90 rounded-md p-8 sm:p-12 text-center space-y-4 shadow-sm border-t-4 border-amber-400">
                    <div class="w-12 h-12 rounded bg-amber-100 text-amber-800 flex items-center justify-center mx-auto text-xl font-bold">
                        ℹ️
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">No Advertised Opportunities Currently Available</h3>
                    <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                        There are currently no specific advertised internship vacancies matching your search criteria. However, zero advertised vacancies NEVER prevent general applications.
                    </p>
                    <div class="pt-3">
                        @if($hasReachedMax)
                            <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                                    class="inline-block px-7 py-3 rounded-md bg-slate-300 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-not-allowed shadow-none">
                                Submit General Application
                            </button>
                        @elseif(!$hasGeneralApp && !$isAdmin)
                            <a href="{{ route('application.selectType') }}" class="inline-block px-7 py-3 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md transition-all">
                                Submit General Application &rarr;
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-block px-7 py-3 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-md transition-all">
                                Go to My Portal Dashboard &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Advertised Positions ({{ $opportunities->total() }})
                        </h2>
                    </div>

                    {{-- Clean Vertical Listing --}}
                    <div class="bg-white border border-slate-200/90 rounded-md divide-y divide-slate-200 shadow-sm">
                        @foreach($opportunities as $opp)
                            @php
                                $isWrongDept = $appliedDeptId && (int)$opp->department_id !== (int)$appliedDeptId;
                            @endphp
                            <div class="p-6 sm:p-7 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                <div class="space-y-2.5 flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold text-[#005A2B] uppercase tracking-wider">
                                            {{ $opp->department->name ?? 'Ministry Division' }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                                        <a href="{{ route('opportunities.show', $opp->id) }}" class="hover:text-[#005A2B] transition-colors">
                                            {{ $opp->title }}
                                        </a>
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-slate-600 font-medium">
                                        <span>Duration: <strong class="text-slate-900">{{ $opp->duration_months }} Months</strong></span>
                                        <span class="text-slate-300">|</span>
                                        <span>Available Positions: <strong class="text-slate-900">{{ $opp->positions_count }}</strong></span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-3xl pt-1">
                                        {{ Str::limit($opp->description, 160) }}
                                    </p>
                                </div>
                                <div class="shrink-0 pt-2 lg:pt-0">
                                    @if($hasReachedMax)
                                        <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                                                class="w-full sm:w-auto px-6 py-3 rounded-md bg-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            View Position (Max Reached)
                                        </button>
                                    @elseif($isWrongDept)
                                        <button type="button" onclick="showToast('You can only apply for opportunities in your registered department ({{ $appliedDeptName }}).', 'warning')"
                                                class="w-full sm:w-auto px-6 py-3 rounded-md bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            {{ $opp->department->code ?? 'Other' }} Department Only
                                        </button>
                                    @else
                                        <a href="{{ route('opportunities.show', $opp->id) }}"
                                           class="w-full sm:w-auto px-6 py-3 rounded-md bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-sm hover:shadow-md transition-all inline-block text-center">
                                            View Position & Apply &rarr;
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8">
                    {{ $opportunities->links() }}
                </div>
            @endif

        </div>
    </section>

@endsection

