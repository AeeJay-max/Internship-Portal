@extends('application.layout')

@section('step-content')
<div class="space-y-8">
    <div class="border-b border-emerald-100 pb-4">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-700 text-white font-bold text-sm">6</span>
            <div>
                <h2 class="text-2xl font-black text-slate-900">Step 6 — Review & Submit Application</h2>
                <p class="text-xs text-slate-600">Review all details carefully before final submission to the Ministry of Sports, Recreation, Arts and Culture.</p>
            </div>
        </div>
    </div>

    @if(!$application->isDraft())
        <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 space-y-1">
            <h3 class="font-bold text-base">Application Reference: {{ $application->reference_number }}</h3>
            <p class="text-xs text-emerald-700">Submitted on {{ optional($application->submitted_at)->format('F d, Y \a\t H:i') }}</p>
        </div>
    @endif

    @if($application->isDraft() && !$canSubmit)
        <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 space-y-3">
            <h3 class="font-bold text-sm">Your application is incomplete:</h3>
            <ul class="space-y-2 text-xs">
                @foreach($missing as $item)
                    <li class="flex items-center justify-between">
                        <span class="text-rose-700 font-semibold">• {{ $item['section'] }}</span>
                        <a href="{{ $item['route'] }}" class="px-3 py-1 rounded bg-rose-600 text-white font-bold text-xs hover:bg-rose-700">
                            Complete Now
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Section 1: Personal --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Personal Details</h3>
            @if($application->isDraft())
                <a href="{{ route('application.personal') }}" class="text-xs font-bold text-emerald-700 hover:underline">Edit</a>
            @endif
        </div>
        @if($p = $application->personalInfo)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs text-slate-700">
                <p><strong>Full Name:</strong> {{ $p->first_name }} {{ $p->middle_name }} {{ $p->last_name }}</p>
                <p><strong>Date of Birth:</strong> {{ optional($p->date_of_birth)->format('d M Y') }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($p->gender ?? '') }}</p>
                <p><strong>National ID:</strong> {{ $p->national_id ?? '—' }}</p>
                <p><strong>Phone:</strong> {{ $p->phone ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $application->user->email ?? '—' }}</p>
                <p><strong>City/Town:</strong> {{ $p->city ?? '—' }}</p>
                <p><strong>Province:</strong> {{ $p->province ?? '—' }}</p>
                <p><strong>Emergency Contact:</strong> {{ $p->emergency_contact_name }} ({{ $p->emergency_contact_phone }})</p>
                <p class="md:col-span-3"><strong>Address:</strong> {{ $p->address }}</p>
            </div>
        @endif
    </div>

    {{-- Section 2: Academic --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Academic Information</h3>
            @if($application->isDraft())
                <a href="{{ route('application.academic') }}" class="text-xs font-bold text-emerald-700 hover:underline">Edit</a>
            @endif
        </div>
        @if($a = $application->academicInfo)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs text-slate-700">
                <p><strong>Institution:</strong> {{ $a->school_name }} ({{ $a->institution_type }})</p>
                <p><strong>Programme:</strong> {{ $a->program_of_study }}</p>
                <p><strong>Field of Study:</strong> {{ $a->field_of_study }}</p>
                <p><strong>Current Level:</strong> {{ $a->current_year_level }}</p>
                <p><strong>Qualification:</strong> {{ $a->academic_qualification }}</p>
                <p><strong>Expected Graduation:</strong> {{ optional($a->expected_graduation_date)->format('M Y') ?? '—' }}</p>
            </div>
        @endif
    </div>

    {{-- Section 3: Internship Preferences --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Internship Preferences</h3>
            @if($application->isDraft())
                <a href="{{ route('application.preferences') }}" class="text-xs font-bold text-emerald-700 hover:underline">Edit</a>
            @endif
        </div>
        @if($pref = $application->preference)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-700">
                <p><strong>Preferred Department:</strong> <span class="font-bold text-emerald-900">{{ $pref->preferredDepartment->name ?? 'None' }}</span></p>
                <p><strong>Second Preference:</strong> {{ $pref->secondPreferredDepartment->name ?? 'None' }}</p>
                <p><strong>Preferred Dates:</strong> {{ optional($pref->preferred_start_date)->format('d M Y') }} to {{ optional($pref->preferred_end_date)->format('d M Y') }}</p>
                <p><strong>Required Duration:</strong> {{ $pref->required_duration ?? 'Not specified' }}</p>
                <p><strong>Preferred Location:</strong> {{ $pref->preferred_location ?? 'Harare' }}</p>
                <p><strong>Department Flexibility:</strong> {{ $pref->flexible_department ? 'Flexible to work in other departments' : 'Only preferred department' }}</p>
            </div>
        @endif
    </div>

    {{-- Section 4: Motivation --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Motivation Statement</h3>
            @if($application->isDraft())
                <a href="{{ route('application.motivation') }}" class="text-xs font-bold text-emerald-700 hover:underline">Edit</a>
            @endif
        </div>
        @if($pref = $application->preference)
            <div class="text-xs text-slate-700 space-y-3">
                <div>
                    <strong class="block text-slate-900 mb-1">Motivation:</strong>
                    <p class="whitespace-pre-line bg-white p-3 rounded-lg border border-slate-200">{{ $pref->motivation_statement }}</p>
                </div>
                @if($pref->career_objectives)
                    <div>
                        <strong class="block text-slate-900 mb-1">Career Objectives:</strong>
                        <p class="whitespace-pre-line bg-white p-3 rounded-lg border border-slate-200">{{ $pref->career_objectives }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Section 5: Documents --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Uploaded Supporting Documents (5-in-1 PDF)</h3>
            @if($application->isDraft())
                <a href="{{ route('application.documents') }}" class="text-xs font-bold text-emerald-700 hover:underline">Edit Document</a>
            @endif
        </div>
        <div class="space-y-3">
            @php $doc = $application->documents()->latest()->first(); @endphp
            @if($doc)
                <div class="flex justify-between items-center py-3 px-4 bg-white rounded-xl border border-slate-200 text-xs flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold text-[10px] flex items-center justify-center">PDF</span>
                        <div>
                            <span class="font-bold text-slate-800 flex items-center gap-2">
                                Combined 5-in-1 Supporting Documents
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded font-bold">Uploaded ✓</span>
                            </span>
                            <span class="text-[11px] text-slate-500">{{ $doc->file_name ?? 'Combined_Documents.pdf' }} ({{ round(($doc->file_size ?? 0) / 1024, 1) }} KB)</span>
                        </div>
                    </div>
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
                </div>

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
                <p class="text-xs text-rose-600 font-bold">⚠️ No single combined PDF uploaded. You cannot submit without uploading your combined PDF file.</p>
            @endif
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.documents') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition">
            &larr; Back to Documents
        </a>

        @if($application->isDraft() && $canSubmit)
            <form method="POST" action="{{ route('application.submit') }}">
                @csrf
                <button type="submit" class="px-10 py-4 rounded-xl bg-emerald-700 text-white font-extrabold text-sm uppercase tracking-wider hover:bg-emerald-800 transition shadow-lg inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Submit Internship Application</span>
                </button>
            </form>
        @elseif($application->isDraft())
            <button disabled class="px-8 py-3.5 rounded-xl bg-slate-300 text-slate-500 font-bold text-xs uppercase cursor-not-allowed">
                Complete Required Sections First
            </button>
        @endif
    </div>
</div>
@endsection

