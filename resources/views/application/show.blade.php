@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Application {{ $application->reference_number }}</h1>
                <p class="text-xs text-slate-500">Ministry of Sport, Recreation, Arts & Culture</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-blue-900 font-bold text-xs uppercase tracking-wider hover:underline">&larr; Back to Dashboard</a>
        </div>

        {{-- Status Badge Card --}}
        @php $badge = $application->statusBadge(); @endphp
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase font-bold text-slate-400 block mb-1">Current Application Status</span>
                <span class="px-4 py-1.5 rounded-full text-sm font-extrabold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
            </div>
            <div class="text-xs text-slate-500 text-right">
                <p>Submitted: <strong>{{ optional($application->submitted_at)->format('d M Y \a\t H:i') ?? 'Not submitted' }}</strong></p>
                <p>Preferred Dept: <strong>{{ $application->preference->preferredDepartment->name ?? 'General' }}</strong></p>
            </div>
        </div>

        {{-- PLACEMENT DETAILS CARD (Visible when Placed) --}}
        @if($application->isPlaced() && $application->placement)
            @php $pl = $application->placement; @endphp
            <div class="bg-emerald-900 text-white rounded-2xl p-8 shadow-xl space-y-6">
                <div class="flex items-center gap-3 border-b border-emerald-700/60 pb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-700 flex items-center justify-center font-bold text-lg text-white">
                        ✓
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold">Official Internship Placement Details</h3>
                        <p class="text-xs text-emerald-200">You have been assigned an official Ministry internship attachment.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="text-xs text-emerald-300 uppercase font-semibold block mb-1">Assigned Ministry Department</span>
                        <p class="font-bold text-base text-white">{{ $pl->department->name ?? 'Ministry' }}</p>
                        @if($pl->division_unit)
                            <p class="text-xs text-emerald-200 mt-0.5">Division / Unit: {{ $pl->division_unit }}</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs text-emerald-300 uppercase font-semibold block mb-1">Placement Location</span>
                        <p class="font-bold text-base text-white">{{ $pl->placement_location ?? 'Harare HQ' }}</p>
                    </div>

                    <div>
                        <span class="text-xs text-emerald-300 uppercase font-semibold block mb-1">Assigned Supervisor</span>
                        <p class="font-bold text-base text-white">{{ $pl->supervisor_name }}</p>
                        <p class="text-xs text-emerald-200">{{ $pl->supervisor_email }} | {{ $pl->supervisor_phone }}</p>
                    </div>

                    <div>
                        <span class="text-xs text-emerald-300 uppercase font-semibold block mb-1">Internship Period</span>
                        <p class="font-bold text-base text-white">{{ optional($pl->start_date)->format('d M Y') }} — {{ optional($pl->end_date)->format('d M Y') }}</p>
                        <p class="text-xs text-emerald-200">Duration: {{ $pl->duration ?? 'As per agreement' }}</p>
                    </div>
                </div>

                @if($pl->reporting_instructions)
                    <div class="pt-4 border-t border-emerald-700/60">
                        <span class="text-xs text-emerald-300 uppercase font-semibold block mb-2">First Day Reporting Instructions</span>
                        <div class="p-4 rounded-xl bg-emerald-950/60 border border-emerald-700/40 text-xs text-emerald-100 leading-relaxed whitespace-pre-line">
                            {{ $pl->reporting_instructions }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Personal --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">Personal Information</h3>
            @if($p = $application->personalInfo)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-700">
                    <p><strong>Name:</strong> {{ $p->first_name }} {{ $p->middle_name }} {{ $p->last_name }}</p>
                    <p><strong>National ID:</strong> {{ $p->national_id }}</p>
                    <p><strong>Phone:</strong> {{ $p->phone }}</p>
                    <p><strong>City / Province:</strong> {{ $p->city }}, {{ $p->province }}</p>
                    <p><strong>Emergency Contact:</strong> {{ $p->emergency_contact_name }} ({{ $p->emergency_contact_phone }})</p>
                    <p class="md:col-span-2"><strong>Address:</strong> {{ $p->address }}</p>
                </div>
            @endif
        </div>

        {{-- Academic --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">Academic Background</h3>
            @if($a = $application->academicInfo)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-700">
                    <p><strong>Institution:</strong> {{ $a->school_name }} ({{ $a->institution_type }})</p>
                    <p><strong>Programme:</strong> {{ $a->program_of_study }}</p>
                    <p><strong>Field:</strong> {{ $a->field_of_study }}</p>
                    <p><strong>Level:</strong> {{ $a->current_year_level }}</p>
                    <p><strong>Qualification:</strong> {{ $a->academic_qualification }}</p>
                </div>
            @endif
        </div>

        {{-- Preferences & Motivation --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">Internship Preferences & Motivation</h3>
            @if($pref = $application->preference)
                <div class="text-xs text-slate-700 space-y-3">
                    <p><strong>Preferred Department:</strong> {{ $pref->preferredDepartment->name ?? 'General' }}</p>
                    <p><strong>Required Duration:</strong> {{ $pref->required_duration }}</p>
                    <div>
                        <strong>Motivation Statement:</strong>
                        <p class="mt-1 p-3 rounded-lg bg-slate-50 border border-slate-200 whitespace-pre-line">{{ $pref->motivation_statement }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">Submitted Supporting Documents</h3>
            <div class="space-y-2">
                @forelse($application->documents as $doc)
                    <div class="flex justify-between items-center py-2 px-3 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                        <span class="font-semibold text-slate-800">{{ $doc->documentType->name ?? 'Document' }}</span>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="font-bold text-blue-900 underline">View File &rarr;</a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">No documents uploaded.</p>
                @endforelse
            </div>
        </div>

        {{-- Application Logs --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">Application Activity Log</h3>
            <div class="space-y-3">
                @forelse($application->logs as $log)
                    <div class="text-xs text-slate-600 flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-900 mt-1.5 shrink-0"></span>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $log->action }}</p>
                            <p class="text-slate-400 text-[10px]">{{ $log->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">No log entries.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
