@extends('layouts.app')

@section('content')
<div class="bg-slate-900 py-12 text-white">
    <div class="max-w-4xl mx-auto px-6 space-y-3">
        <span class="text-xs font-bold px-3 py-1 rounded bg-blue-500/20 text-blue-300 border border-blue-400/30 uppercase">
            {{ $opportunity->department->name ?? 'Ministry' }}
        </span>
        <h1 class="text-3xl font-extrabold">{{ $opportunity->title }}</h1>
        <p class="text-slate-300 text-sm">Duration: {{ $opportunity->duration_months }} Months | Positions: {{ $opportunity->positions_count }}</p>
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
            <a href="{{ route('application.selectType', ['opportunity' => $opportunity->id]) }}"
               class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm hover:bg-blue-800 shadow transition">
                Apply for this Opportunity
            </a>
        </div>
    </div>
</div>
@endsection
