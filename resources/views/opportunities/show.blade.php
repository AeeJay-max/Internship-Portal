@extends('layouts.app')

@section('content')
<div class="py-12 text-white" style="background: linear-gradient(135deg, #00421F 0%, #005A2B 100%); border-bottom: 4px solid #f59e0b;">
    <div class="max-w-4xl mx-auto px-6 space-y-3">
        <span class="text-xs font-extrabold px-3 py-1 rounded bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase tracking-widest">
            {{ $opportunity->department->name ?? 'Ministry' }}
        </span>
        <h1 class="text-3xl font-extrabold text-white">{{ $opportunity->title }}</h1>
        <p class="text-slate-100 text-sm font-medium">Duration: {{ $opportunity->duration_months }} Months | Positions: {{ $opportunity->positions_count }}</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 py-12 space-y-8">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Description</h3>
            <p class="text-slate-600 text-base leading-relaxed whitespace-pre-line">{{ $opportunity->description }}</p>
        </div>

        @if($opportunity->requirements)
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Requirements</h3>
                <p class="text-slate-600 text-base leading-relaxed whitespace-pre-line">{{ $opportunity->requirements }}</p>
            </div>
        @endif

        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-xs text-slate-400">Department Contact: {{ $opportunity->department->contact_email ?? 'internships@mosrac.gov.zw' }}</p>
            </div>
            @php
                $user = Auth::user();
                $isAdmin = $user && $user->isAdmin();
                $hasReachedMax = $user && !$isAdmin && $user->hasReachedMaxApplications();
                $hasOppApp = $user && !$isAdmin && $user->hasOpportunityApplication();
                $appliedDeptId = $user && !$isAdmin ? $user->getAppliedDepartmentId() : null;
                $isWrongDept = $appliedDeptId && (int)$opportunity->department_id !== (int)$appliedDeptId;
                $appliedDeptName = $user && !$isAdmin ? ($user->getAppliedDepartment()?->name ?? 'your department') : '';
            @endphp

            @if($hasReachedMax)
                <button type="button" onclick="showToast('Maximum number of application has been reached.', 'warning')"
                        class="px-8 py-3.5 rounded-xl bg-slate-300 text-slate-500 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none">
                    Apply for this Opportunity (Max Reached)
                </button>
            @elseif($hasOppApp)
                <button type="button" onclick="showToast('You have already submitted an application for a published opportunity.', 'warning')"
                        class="px-8 py-3.5 rounded-xl bg-slate-300 text-slate-500 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none">
                    Opportunity Application Submitted
                </button>
            @elseif($isWrongDept)
                <button type="button" onclick="showToast('You can only apply for internship opportunities within your registered department ({{ $appliedDeptName }}).', 'warning')"
                        class="px-8 py-3.5 rounded-xl bg-amber-100 text-amber-800 border border-amber-300 font-extrabold text-xs uppercase tracking-wider cursor-not-allowed shadow-none">
                    Department Restricted
                </button>
            @else
                <form method="POST" action="{{ route('application.createFromType') }}">
                    @csrf
                    <input type="hidden" name="opportunity_id" value="{{ $opportunity->id }}">
                    <button type="submit" class="px-8 py-3.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider shadow transition">
                        Apply for this Opportunity &rarr;
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
