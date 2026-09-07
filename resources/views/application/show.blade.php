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

        {{-- INTERVIEW SCHEDULED CARD --}}
        @if($application->status === 'interview_required')
            <div class="bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-950 text-white rounded-2xl p-6 md:p-8 shadow-xl space-y-6 border border-blue-600/40">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-blue-700/50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-blue-600 flex items-center justify-center font-extrabold text-xl text-white shadow">
                            📅
                        </div>
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-400 text-slate-950">Action Required</span>
                            <h3 class="text-2xl font-extrabold text-white mt-1">Ministry Internship Interview Scheduled</h3>
                            <p class="text-xs text-blue-200">Ministry of Sport, Recreation, Arts & Culture</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 self-start md:self-auto">
                        Status: Interview Scheduled
                    </span>
                </div>

                {{-- Scheduled Date, Time & Venue --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Interview Date</span>
                        <p class="text-base font-extrabold text-white flex items-center gap-2">
                            <span>📆</span>
                            {{ $application->interview_date ? $application->interview_date->format('l, d F Y') : 'Date to be confirmed' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Interview Time</span>
                        <p class="text-base font-extrabold text-white flex items-center gap-2">
                            <span>⏰</span>
                            {{ $application->interview_time ? \Carbon\Carbon::parse($application->interview_time)->format('g:i A') : 'Time to be confirmed' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Venue / Location</span>
                        <p class="text-xs font-bold text-white leading-snug flex items-start gap-1.5">
                            <span class="shrink-0 mt-0.5">📍</span>
                            <span>{{ $application->interview_location ?? 'Chinengundu Mashayamombe Building, 95 Cnr N.Mandela & S. V. Muzenda Street, Harare' }}</span>
                        </p>
                    </div>
                </div>

                @if($application->review_notes)
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-300">Ministry Interview Instructions:</h4>
                        <div class="p-4 rounded-xl bg-slate-900/90 border border-blue-800/60 text-xs text-blue-100 whitespace-pre-line leading-relaxed">
                            {{ $application->review_notes }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- ACCEPTANCE & ONBOARDING CARD --}}
        @if(in_array($application->status, ['placement_pending', 'approved', 'placed']))
            <div class="bg-gradient-to-br from-emerald-900 via-slate-900 to-emerald-950 text-white rounded-2xl p-6 md:p-8 shadow-xl space-y-6 border border-emerald-700/40">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-emerald-700/50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-500 flex items-center justify-center font-bold text-xl text-white shadow">
                            ✓
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-white">Application Approved</h3>
                            <p class="text-xs text-emerald-200">Ministry of Sport, Recreation, Arts & Culture</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 self-start md:self-auto">
                        Status: {{ $application->isPlaced() ? 'Official Placement Assigned' : 'Placement Pending' }}
                    </span>
                </div>

                @if($application->review_notes)
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-300">Acceptor Instructions & Required Documents to Bring:</h4>
                        <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-700/50 text-xs text-emerald-100 whitespace-pre-line leading-relaxed">
                            {{ $application->review_notes }}
                        </div>
                    </div>
                @endif

                {{-- ATTACH ONBOARDING DOCUMENTS FORM --}}
                <div class="bg-emerald-950/60 rounded-xl p-5 border border-emerald-700/40 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Attach / Upload Requested Onboarding Documents
                        </h4>
                        <p class="text-xs text-emerald-200 mt-0.5">Upload any requested documents (e.g. University Recommendation Letter, Certified Transcripts, Medical Clearance, Bank Details) to attach to your application file.</p>
                    </div>

                    <form method="POST" action="{{ route('application.uploadOnboardingDocument', $application->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-emerald-200 uppercase mb-1">Document Description / Title</label>
                            <input type="text" name="document_name" required placeholder="e.g. University Recommendation Letter" class="w-full bg-slate-900/90 border border-emerald-700/60 rounded-lg px-3 py-2 text-xs text-white placeholder-emerald-400/50 focus:outline-none focus:border-emerald-400">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-emerald-200 uppercase mb-1">Select File (PDF / Image / Doc)</label>
                            <input type="file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="w-full text-xs text-emerald-200 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-600">
                        </div>

                        <div>
                            <button type="submit" class="w-full py-2 px-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-lg transition shadow">
                                Upload Document &rarr;
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

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
            <div class="flex items-center justify-between border-b border-slate-100 pb-2 flex-wrap gap-2">
                <h3 class="font-bold text-slate-900 text-base">Submitted Supporting Documents (5-in-1 PDF)</h3>
                @php $doc = $application->documents()->latest()->first(); @endphp
                @if($doc)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('application.documents.view', $doc->id) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-bold text-xs transition inline-flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Fullscreen View
                        </a>
                        <a href="{{ route('application.documents.download', $doc->id) }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-bold text-xs transition inline-flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Copy
                        </a>
                    </div>
                @endif
            </div>

            @if($doc)
                {{-- Internal PDF Viewer --}}
                <div class="rounded-xl overflow-hidden border border-slate-300 bg-white shadow-inner">
                    <object data="{{ route('application.documents.view', $doc->id) }}" type="application/pdf" class="w-full h-[550px]">
                        <iframe src="{{ route('application.documents.view', $doc->id) }}" class="w-full h-[550px] border-0">
                            <div class="p-6 text-center text-slate-500 bg-slate-50">
                                <p class="text-xs font-bold mb-3">Unable to embed PDF viewer directly in browser.</p>
                                <a href="{{ route('application.documents.view', $doc->id) }}" target="_blank" class="px-3.5 py-2 bg-slate-800 text-white rounded-lg font-bold text-xs inline-flex items-center gap-1.5 mr-2">
                                    Open PDF in New Tab
                                </a>
                                <a href="{{ route('application.documents.download', $doc->id) }}" class="px-3.5 py-2 bg-emerald-700 text-white rounded-lg font-bold text-xs inline-flex items-center gap-1.5">
                                    Download PDF
                                </a>
                            </div>
                        </iframe>
                    </object>
                </div>
            @else
                <p class="text-xs text-amber-600 font-semibold">⚠️ No documents uploaded.</p>
            @endif
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
