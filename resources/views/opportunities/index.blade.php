@extends('layouts.app')

@section('content')
<div class="bg-slate-900 py-12 text-white">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-extrabold">Advertised Internship Opportunities</h1>
        <p class="text-slate-300 mt-2">Browse specific internship positions opened by Ministry departments.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('opportunities.index') }}" class="mb-10 p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or keyword..." class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-full md:w-64">
            <select name="department_id" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-blue-900 text-white font-bold text-sm hover:bg-blue-800 transition">
            Filter
        </button>
    </form>

    {{-- General Application Callout --}}
    <div class="mb-8 p-4 rounded-xl bg-blue-50 border border-blue-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-blue-900">
            <strong>Can't find a matching vacancy?</strong> You can still submit a general application to any department.
        </div>
        <a href="{{ route('application.selectType') }}" class="px-5 py-2.5 rounded-lg bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800 shrink-0">
            Apply General
        </a>
    </div>

    @if($opportunities->isEmpty())
        <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-500">
            No advertised opportunities matching your filter. Submit a general application above.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($opportunities as $opp)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div class="space-y-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-100 text-blue-800">
                            {{ $opp->department->name ?? 'Ministry' }}
                        </span>
                        <h3 class="text-lg font-bold text-slate-900">{{ $opp->title }}</h3>
                        <div class="text-xs text-slate-500 space-y-1">
                            <p>Duration: <strong>{{ $opp->duration_months }} Months</strong></p>
                            <p>Available Positions: <strong>{{ $opp->positions_count }}</strong></p>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($opp->description, 120) }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('opportunities.show', $opp->id) }}" class="block w-full text-center py-2.5 rounded-xl bg-blue-900 text-white font-bold text-xs hover:bg-blue-800 transition">
                            View Opportunity Details
                        </a>
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
