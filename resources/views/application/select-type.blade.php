@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Start Internship Application</h1>
        <p class="text-slate-600 mt-1">Select your application preference below. General applications are always accepted.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Option 1: General Application --}}
        <div class="bg-white p-8 rounded-2xl border-2 border-blue-600 shadow-md flex flex-col justify-between">
            <div class="space-y-4">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-bold text-xs uppercase tracking-wider">
                    Option 1 — Recommended
                </span>
                <h2 class="text-xl font-bold text-slate-900">General Internship Application</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Apply directly to the Ministry by selecting your preferred department. No advertised vacancy is required. Your application enters the Ministry general placement pool.
                </p>

                <form method="POST" action="{{ route('application.createFromType') }}" class="pt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Select Preferred Department</label>
                        <select name="preferred_department_id" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Select Preferred Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (isset($opportunity) && $opportunity->department_id == $dept->id) ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
                        Start General Application &rarr;
                    </button>
                </form>
            </div>
        </div>

        {{-- Option 2: Advertised Opportunity --}}
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="space-y-4">
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs uppercase tracking-wider">
                    Option 2 — Specific Vacancy
                </span>
                <h2 class="text-xl font-bold text-slate-900">Apply for Advertised Opportunity</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Apply directly for an advertised vacancy with a specified duration and requirements.
                </p>

                @if($opportunities->isEmpty())
                    <div class="p-4 rounded-xl bg-slate-50 text-slate-500 text-xs text-center border border-slate-200">
                        No advertised vacancies right now. Use the General Application on the left.
                    </div>
                @else
                    <form method="POST" action="{{ route('application.createFromType') }}" class="pt-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Select Vacancy</label>
                            <select name="opportunity_id" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">-- Select Advertised Vacancy --</option>
                                @foreach($opportunities as $opp)
                                    <option value="{{ $opp->id }}" {{ (isset($opportunity) && $opportunity->id == $opp->id) ? 'selected' : '' }}>
                                        {{ $opp->title }} ({{ $opp->department->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-slate-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-slate-800 transition shadow">
                            Apply to Vacancy &rarr;
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
