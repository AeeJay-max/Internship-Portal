@extends('layouts.app')

@section('content')
<div class="py-16 text-white" style="background: linear-gradient(135deg, #00421F 0%, #005A2B 100%); border-bottom: 4px solid #f59e0b;">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-xs font-extrabold uppercase tracking-widest text-amber-300 mb-2">MoSRAC Placements</p>
        <h1 class="text-4xl font-extrabold text-white">Internship Opportunities</h1>
        <p class="text-sm text-slate-200 mt-2 max-w-2xl mx-auto">
            Browse specific internship positions opened by Ministry departments or submit a general application.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 space-y-8">

    {{-- Search & Department Filter --}}
    <form method="GET" action="{{ route('internships.index') }}" class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by position title or keyword..." class="w-full rounded-xl border-slate-300 text-xs focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <div class="w-full md:w-64">
            <select name="department_id" class="w-full rounded-xl border-slate-300 text-xs focus:ring-emerald-600 focus:border-emerald-600">
                <option value="">All Ministry Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider transition shadow">
            Filter Positions
        </button>
    </form>

    @php
        $user = Auth::user();
        $isAdmin = $user && $user->isAdmin();
        $hasReachedMax = $user && !$isAdmin && $user->hasReachedMaxApplications();
        $hasGeneralApp = $user && !$isAdmin && $user->hasGeneralApplication();
        $hasOppApp = $user && !$isAdmin && $user->hasOpportunityApplication();
        $appliedDeptId = $user && !$isAdmin ? $user->getAppliedDepartmentId() : null;
        $appliedDeptName = $user && !$isAdmin ? ($user->getAppliedDepartment()?->name ?? 'your department') : '';
    @endphp

    {{-- General Application Notice Card --}}
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white p-8 rounded-2xl border border-emerald-700 shadow-md mb-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-amber-400/20 text-amber-300 text-[10px] font-extrabold uppercase tracking-widest rounded-full">Continuous Open Application</span>
            <h2 class="text-2xl font-extrabold text-white">Submit General Internship Application</h2>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Applicants can submit a general internship application at any time, even when no specific vacancy is advertised for their preferred department.
            </p>
        </div>
        @if($hasReachedMax)
            <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                    class="px-6 py-3 rounded-xl bg-slate-400 text-slate-200 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none shrink-0">
                General Application (Max Reached)
            </button>
        @elseif($hasGeneralApp)
            <button type="button" onclick="showToast('You have already submitted a general internship application.', 'warning')"
                    class="px-6 py-3 rounded-xl bg-slate-400 text-slate-200 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none shrink-0">
                General Application Submitted
            </button>
        @elseif(!$isAdmin)
            <a href="{{ route('application.selectType') }}" class="px-6 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-extrabold text-xs uppercase tracking-wider shadow shrink-0">
                Submit General Application &rarr;
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-extrabold text-xs uppercase tracking-wider shadow shrink-0">
                Go to Portal Dashboard &rarr;
            </a>
        @endif
    </div>

    @if($opportunities->isEmpty())
        <div class="bg-white p-12 rounded-2xl border-2 border-dashed border-slate-300 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mx-auto text-2xl font-black">
                ℹ️
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">No Advertised Opportunities Currently Available</h3>
            <p class="text-xs text-slate-600 max-w-lg mx-auto leading-relaxed">
                There are currently no specific advertised internship vacancies matching your criteria. However, zero advertised vacancies NEVER prevent general applications.
            </p>
            <div class="pt-2">
                @if($hasReachedMax)
                    <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                            class="inline-block px-8 py-3.5 rounded-xl bg-slate-300 text-slate-500 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none">
                        Submit General Internship Application
                    </button>
                @elseif(!$hasGeneralApp && !$isAdmin)
                    <a href="{{ route('application.selectType') }}" class="inline-block px-8 py-3.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider shadow">
                        Submit General Internship Application
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider shadow">
                        Go to My Portal Dashboard
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($opportunities as $opp)
                @php
                    $isWrongDept = $appliedDeptId && (int)$opp->department_id !== (int)$appliedDeptId;
                @endphp
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-3">
                        <span class="text-[10px] font-extrabold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                            {{ $opp->department->name ?? 'Ministry' }}
                        </span>
                        <h3 class="text-lg font-bold text-slate-900">{{ $opp->title }}</h3>
                        <div class="text-xs text-slate-500 space-y-1">
                            <p>Duration: <strong>{{ $opp->duration_months }} Months</strong></p>
                            <p>Available Positions: <strong>{{ $opp->positions_count }}</strong></p>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ Str::limit($opp->description, 120) }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        @if($hasReachedMax)
                            <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')" class="block w-full text-center py-2.5 rounded-xl bg-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                View Position (Max Reached)
                            </button>
                        @elseif($isWrongDept)
                            <button type="button" onclick="showToast('You can only apply for opportunities in your registered department ({{ $appliedDeptName }}).', 'warning')" class="block w-full text-center py-2.5 rounded-xl bg-amber-100 text-amber-800 border border-amber-300 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                {{ $opp->department->code ?? 'Other' }} Department Only
                            </button>
                        @else
                            <a href="{{ route('opportunities.show', $opp->id) }}" class="block w-full text-center py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition">
                                View Position & Apply
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $opportunities->links() }}
        </div>
    @endif
</div>
@endsection
