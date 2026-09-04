@extends('application.layout')

@section('step-content')
<div class="space-y-8">
    <div class="border-b border-slate-200 pb-4">
        <h2 class="text-2xl font-extrabold text-slate-900">Step 6 — Review & Submit Application</h2>
        <p class="text-xs text-slate-500">Review all information carefully before final submission to the Ministry.</p>
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
                <a href="{{ route('application.personal') }}" class="text-xs font-bold text-blue-700 hover:underline">Edit</a>
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
                <a href="{{ route('application.academic') }}" class="text-xs font-bold text-blue-700 hover:underline">Edit</a>
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
                <a href="{{ route('application.preferences') }}" class="text-xs font-bold text-blue-700 hover:underline">Edit</a>
            @endif
        </div>
        @if($pref = $application->preference)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-700">
                <p><strong>Preferred Department:</strong> <span class="font-bold text-blue-900">{{ $pref->preferredDepartment->name ?? 'None' }}</span></p>
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
                <a href="{{ route('application.motivation') }}" class="text-xs font-bold text-blue-700 hover:underline">Edit</a>
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
            <h3 class="font-bold text-slate-900 text-base">Uploaded Supporting Documents</h3>
            @if($application->isDraft())
                <a href="{{ route('application.documents') }}" class="text-xs font-bold text-blue-700 hover:underline">Edit</a>
            @endif
        </div>
        <div class="space-y-2">
            @forelse($application->documents as $doc)
                <div class="flex justify-between items-center py-2 px-3 bg-white rounded-lg border border-slate-200 text-xs">
                    <span class="font-semibold text-slate-800 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $doc->documentType->name ?? 'Document' }}
                    </span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="font-bold text-blue-700 underline">
                        View Document &rarr;
                    </a>
                </div>
            @empty
                <p class="text-xs text-rose-600">No documents uploaded.</p>
            @endforelse
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.documents') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Documents
        </a>

        @if($application->isDraft() && $canSubmit)
            <form method="POST" action="{{ route('application.submit') }}">
                @csrf
                <button type="submit" class="px-10 py-4 rounded-xl bg-emerald-700 text-white font-extrabold text-sm uppercase tracking-wider hover:bg-emerald-800 transition shadow-lg">
                    Submit Internship Application
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
