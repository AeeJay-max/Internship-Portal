@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-slate-900">Application Review</h1>
                                <span class="font-mono bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-md font-semibold">
                                    {{ $application->reference_number }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1">
                                {{ $application->user?->name }} —
                                @if($application->opportunity)
                                    <span class="font-medium text-emerald-700">Advertised Vacancy: {{ $application->opportunity->title }}</span>
                                @else
                                    <span class="font-medium text-purple-700">General Internship Application</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-emerald-700 font-medium hover:underline text-sm flex items-center gap-1">
                            ← Back to Applications
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-center justify-between">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Status & Workflow Banner --}}
                    @php $badge = $application->statusBadge(); @endphp
                    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-center justify-between gap-4 border border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            @if($application->submitted_at)
                                <span class="text-xs text-gray-500">Submitted: {{ $application->submitted_at->format('d M Y H:i') }}</span>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-2">
                            @php
                                $docsPending = !empty($application->flagged_document_ids) || !empty($application->flagged_cert_names);
                                $waitingForReupload = $docsPending && !$application->needs_reupload_review;
                            @endphp

                            {{-- Mark Under Review --}}
                            @if($application->isSubmitted() || $application->isDocumentsRequested())
                                @if($waitingForReupload)
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Waiting for Re-upload
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.applications.underReview', $application->id) }}">
                                        @csrf
                                        <button id="btn-under-review" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-medium hover:bg-slate-900 transition">
                                            Mark Under Review
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Shortlist --}}
                            @if($application->isSubmitted() || $application->isUnderReview())
                                <button onclick="openShortlistModal()" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition">
                                    ⭐ Shortlist
                                </button>
                            @endif

                            {{-- Request Interview --}}
                            @if($application->isSubmitted() || $application->isUnderReview() || $application->isShortlisted())
                                <button onclick="openInterviewModal()" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition">
                                    📅 Schedule Interview
                                </button>
                            @endif

                            {{-- Request Documents --}}
                            @if(($application->isSubmitted() || $application->isUnderReview()) && !$docsPending)
                                <button onclick="openRequestDocsModal()" class="px-3 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-medium hover:bg-amber-700 transition">
                                    📋 Request Documents
                                </button>
                            @endif

                            {{-- Approve (Move to Placement Pending) & Reject --}}
                            @if(!$application->isApproved() && !$application->isPlaced() && !$application->isRejected())
                                @if($docsPending)
                                    <span class="text-xs text-amber-700 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Docs re-upload pending
                                    </span>
                                @else
                                    <button onclick="openApproveModal()" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition">
                                        ✓ Approve Application
                                    </button>
                                    <button onclick="openRejectModal()" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 transition">
                                        ✗ Reject
                                    </button>
                                @endif
                            @endif

                            {{-- Assign / View Placement --}}
                            @if($application->isPlacementPending() || $application->isApproved() || $application->isPlaced())
                                <a href="{{ route('admin.placements.index', ['application_id' => $application->id]) }}" class="px-3 py-1.5 bg-purple-700 text-white rounded-lg text-xs font-medium hover:bg-purple-800 transition flex items-center gap-1">
                                    🏢 {{ $application->placement ? 'Manage Placement' : 'Assign Placement' }}
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Placement Banner if placed --}}
                    @if($application->placement)
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-bold text-purple-900 text-base flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span>
                                    Official Ministry Placement Record
                                </h3>
                                <span class="bg-purple-200 text-purple-900 text-xs px-2.5 py-1 rounded-full font-bold uppercase">
                                    {{ $application->placement->status ?? 'Active' }}
                                </span>
                            </div>
                            <div class="grid md:grid-cols-3 gap-4 text-xs text-purple-900">
                                <div>
                                    <p class="text-purple-600 font-medium">Assigned Department</p>
                                    <p class="font-semibold text-sm">{{ $application->placement->department?->name ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-600 font-medium">Station / Location</p>
                                    <p class="font-semibold text-sm">{{ $application->placement->station ?? 'Head Office' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-600 font-medium">Supervisor</p>
                                    <p class="font-semibold text-sm">{{ $application->placement->supervisor_name ?? '—' }} ({{ $application->placement->supervisor_email ?? 'N/A' }})</p>
                                </div>
                                <div>
                                    <p class="text-purple-600 font-medium">Start Date</p>
                                    <p class="font-semibold text-sm">{{ $application->placement->start_date ? $application->placement->start_date->format('d M Y') : '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-600 font-medium">End Date</p>
                                    <p class="font-semibold text-sm">{{ $application->placement->end_date ? $application->placement->end_date->format('d M Y') : '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-600 font-medium">Placement Code</p>
                                    <p class="font-mono font-semibold text-sm">{{ $application->placement->placement_code ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Rejection note --}}
                    @if($application->isRejected() && $application->review_notes)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <h4 class="text-red-700 font-semibold mb-1 text-sm">Rejection Reason</h4>
                            <p class="text-red-600 text-xs">{{ $application->review_notes }}</p>
                        </div>
                    @endif

                    {{-- Internship Preferences --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                        <h3 class="font-semibold text-slate-900 text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Internship Preferences
                        </h3>
                        @if($application->preference)
                            <div class="grid md:grid-cols-2 gap-4 text-xs text-gray-700">
                                <p><strong>Preferred Department:</strong> {{ $application->preference->department?->name ?? 'No Preference' }}</p>
                                <p><strong>Primary Focus Area:</strong> {{ $application->preference->primary_interest_area ?? '—' }}</p>
                                <p><strong>Secondary Focus Area:</strong> {{ $application->preference->secondary_interest_area ?? '—' }}</p>
                                <p><strong>Preferred Start Date:</strong> {{ $application->preference->preferred_start_date ? \Carbon\Carbon::parse($application->preference->preferred_start_date)->format('d M Y') : '—' }}</p>
                                <p><strong>Preferred Duration:</strong> {{ $application->preference->preferred_duration ?? '—' }}</p>
                                <p><strong>Preferred Station:</strong> {{ $application->preference->preferred_station ?? 'Head Office (Harare)' }}</p>
                                <div class="md:col-span-2">
                                    <strong>Key Skills & Competencies:</strong>
                                    <p class="mt-1 bg-gray-50 p-2.5 rounded border border-gray-100 text-gray-800 font-mono text-xs whitespace-pre-wrap">{{ $application->preference->skills_and_competencies ?? 'None listed' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400 text-xs">No internship preferences specified.</p>
                        @endif
                    </div>

                    {{-- Motivation & Career Goals --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                        <h3 class="font-semibold text-slate-900 text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Motivation & Statement
                        </h3>
                        <div class="space-y-4 text-xs">
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-1">Motivation Letter / Personal Statement:</h4>
                                <p class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $application->motivation_letter ?: 'No motivation statement provided.' }}</p>
                            </div>
                            @if($application->career_goals)
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1">Career Objectives:</h4>
                                    <p class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $application->career_goals }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Personal Info --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                        <h3 class="font-semibold text-slate-900 text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Personal Information
                        </h3>
                        @if($application->personalInfo)
                            <div class="grid md:grid-cols-2 gap-4 text-xs text-gray-700">
                                <p><strong>Full Name:</strong> {{ $application->personalInfo->first_name }} {{ $application->personalInfo->last_name }}</p>
                                <p><strong>National ID / Passport:</strong> {{ $application->personalInfo->national_id ?? $application->personalInfo->passport_number ?? '—' }}</p>
                                <p><strong>Date of Birth:</strong> {{ $application->personalInfo->date_of_birth ? \Carbon\Carbon::parse($application->personalInfo->date_of_birth)->format('d M Y') : '—' }}</p>
                                <p><strong>Gender:</strong> {{ ucfirst($application->personalInfo->gender ?? '—') }}</p>
                                <p><strong>Nationality:</strong> {{ $application->personalInfo->nationality ?? 'Zimbabwean' }}</p>
                                <p><strong>Phone:</strong> {{ $application->personalInfo->phone ?? '—' }}</p>
                                <p><strong>Email:</strong> {{ $application->user?->email }}</p>
                                <p><strong>City / Province:</strong> {{ $application->personalInfo->city ?? '—' }}</p>
                                <p class="md:col-span-2"><strong>Residential Address:</strong> {{ $application->personalInfo->address ?? '—' }}</p>
                                @if($application->personalInfo->emergency_contact_name)
                                    <p class="md:col-span-2 text-gray-600 bg-gray-50 p-2 rounded">
                                        <strong>Emergency Contact:</strong> {{ $application->personalInfo->emergency_contact_name }} ({{ $application->personalInfo->emergency_contact_phone }}) — {{ $application->personalInfo->emergency_contact_relationship }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-red-500 text-xs">No personal information provided.</p>
                        @endif
                    </div>

                    {{-- Academic Info --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                        <h3 class="font-semibold text-slate-900 text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            Academic Background
                        </h3>
                        @if($application->academicInfo)
                            <div class="grid md:grid-cols-2 gap-4 text-xs text-gray-700">
                                <p><strong>Tertiary Institution:</strong> {{ $application->academicInfo->school_name ?? '—' }}</p>
                                <p><strong>Degree / Diploma:</strong> {{ $application->academicInfo->qualification_level ?? $application->academicInfo->degree_obtained ?? '—' }}</p>
                                <p><strong>Field of Study / Major:</strong> {{ $application->academicInfo->field_of_study ?? $application->academicInfo->specialization ?? '—' }}</p>
                                <p><strong>Current Academic Year:</strong> {{ $application->academicInfo->current_year ?? '—' }}</p>
                                <p><strong>Expected Graduation:</strong> {{ $application->academicInfo->graduation_date ? \Carbon\Carbon::parse($application->academicInfo->graduation_date)->format('F Y') : '—' }}</p>
                                <p><strong>GPA / Grade Average:</strong> {{ $application->academicInfo->gpa ?? 'N/A' }}</p>
                            </div>
                        @else
                            <p class="text-red-500 text-xs">No academic information provided.</p>
                        @endif
                    </div>

                    {{-- Documents (Single Combined PDF) --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-3 border-b border-slate-100 pb-4">
                            <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Internal Combined Application PDF Viewer (5-in-1 Attachment)
                            </h3>
                            @php $doc = $application->documents()->latest()->first(); @endphp
                            @if($doc)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.applications.documents.view', [$application->id, $doc->id]) }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-bold text-xs transition inline-flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Fullscreen View
                                    </a>
                                    <a href="{{ route('admin.applications.documents.download', [$application->id, $doc->id]) }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-bold text-xs transition inline-flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Download Copy
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($doc)
                            <div class="text-xs text-slate-500 flex items-center justify-between bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                                <span>📄 <strong>{{ $doc->file_name ?? 'Combined_Application_Documents.pdf' }}</strong> ({{ round(($doc->file_size ?? 0) / 1024, 1) }} KB)</span>
                                <span class="text-emerald-700 font-semibold">✓ Includes ID, Academic Results, Current Results, CV & University Letter</span>
                            </div>

                            {{-- Embedded Internal PDF Viewer --}}
                            <div class="rounded-xl overflow-hidden border border-slate-300 bg-white shadow-inner">
                                <object data="{{ route('admin.applications.documents.view', [$application->id, $doc->id]) }}" type="application/pdf" class="w-full h-[650px]">
                                    <iframe src="{{ route('admin.applications.documents.view', [$application->id, $doc->id]) }}" class="w-full h-[650px] border-0">
                                        <div class="p-6 text-center text-slate-500 bg-slate-50">
                                            <p class="text-xs font-bold mb-3">Unable to embed PDF viewer directly in browser.</p>
                                            <a href="{{ route('admin.applications.documents.view', [$application->id, $doc->id]) }}" target="_blank" class="px-3.5 py-2 bg-slate-800 text-white rounded-lg font-bold text-xs inline-flex items-center gap-1.5 mr-2">
                                                Open PDF in New Tab
                                            </a>
                                            <a href="{{ route('admin.applications.documents.download', [$application->id, $doc->id]) }}" class="px-3.5 py-2 bg-emerald-700 text-white rounded-lg font-bold text-xs inline-flex items-center gap-1.5">
                                                Download PDF
                                            </a>
                                        </div>
                                    </iframe>
                                </object>
                            </div>
                        @else
                            <p class="text-amber-600 text-xs font-semibold">⚠️ No supporting PDF document uploaded by applicant.</p>
                        @endif
                    </div>

                    {{-- Timeline / Audit Log --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-semibold text-slate-900 text-base mb-4">Activity & Audit Log</h3>
                        <div class="space-y-3">
                            @forelse($application->logs as $log)
                                <div class="flex gap-3 text-xs">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full mt-1.5 shrink-0"></div>
                                    <div>
                                        <p class="text-gray-800 font-medium">{{ $log->action }}</p>
                                        <p class="text-gray-400 text-[11px] mt-0.5">
                                            {{ $log->created_at->format('d M Y H:i') }}
                                            @if($log->performer) — by {{ $log->performer->name }} @endif
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-xs">No activity logged yet.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            {{-- Shortlist Modal --}}
            <div id="shortlistModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 mx-4">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Shortlist Application</h2>
                    <p class="text-gray-600 text-xs mb-4">Are you sure you want to mark this applicant as shortlisted for potential placement?</p>
                    <form method="POST" action="{{ route('admin.applications.shortlist', $application->id) }}">
                        @csrf
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeShortlistModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700">Confirm Shortlist</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Interview Modal --}}
            <div id="interviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 mx-4">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Request Interview</h2>
                    <p class="text-gray-600 text-xs mb-4">Set candidate status to Interview Required. You can include notes or instructions below.</p>
                    <form method="POST" action="{{ route('admin.applications.requestInterview', $application->id) }}">
                        @csrf
                        <textarea name="review_notes" rows="3" placeholder="Enter interview details or instructions for applicant..." class="w-full border border-gray-300 rounded-lg p-2.5 text-xs mb-4 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeInterviewModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700">Request Interview</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Approve Modal --}}
            <div id="approveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-bold text-slate-900">Approve & Specify Onboarding Documents</h2>
                        <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>
                    <p class="text-gray-600 text-xs mb-4">Approving this application moves it to <strong>Placement Pending</strong> status. Specify the documents the applicant must bring and attach for onboarding.</p>
                    
                    <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Required Onboarding Documents (Applicant Must Bring / Attach):</label>
                            <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Original National ID / Passport & Copies" checked class="rounded text-emerald-600">
                                    <span>Original National ID / Passport & Copies</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Official University Recommendation / Introduction Letter" checked class="rounded text-emerald-600">
                                    <span>Official University Recommendation / Introduction Letter</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Certified Academic Transcripts & Certificates" checked class="rounded text-emerald-600">
                                    <span>Certified Academic Transcripts & Certificates</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Certified Birth Certificate & O/A Level Results" checked class="rounded text-emerald-600">
                                    <span>Certified Birth Certificate & O/A Level Results</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Medical Fitness / Health Clearance Certificate" class="rounded text-emerald-600">
                                    <span>Medical Fitness / Health Clearance Certificate</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Police Clearance Certificate" class="rounded text-emerald-600">
                                    <span>Police Clearance Certificate</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-900">
                                    <input type="checkbox" name="required_docs[]" value="Bank Details / Account Confirmation Statement" class="rounded text-emerald-600">
                                    <span>Bank Details / Account Confirmation Statement</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Additional Acceptor Instructions / Notes:</label>
                            <textarea name="review_notes" rows="3" placeholder="Specify any additional instructions, reporting location, or document guidelines..." class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                            <button type="button" onclick="closeApproveModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" id="btn-approve" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 shadow">Approve & Send Requirements</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 mx-4">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Reject Application</h2>
                    <p class="text-gray-600 text-xs mb-3">Please state the reason for rejection. The applicant will be able to see this reason.</p>
                    <form method="POST" action="{{ route('admin.applications.reject', $application->id) }}">
                        @csrf
                        <textarea name="review_notes" rows="4" required minlength="10" placeholder="Specify reason for rejection..." class="w-full border border-gray-300 rounded-lg p-2.5 text-xs mb-4 focus:ring-red-500 focus:border-red-500"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" id="btn-reject" class="px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700">Confirm Rejection</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Request Docs Modal --}}
            <div id="requestDocsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 mx-4">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Request Document Re-upload</h2>
                    <p class="text-gray-600 text-xs mb-4">Select documents that require re-upload from the candidate.</p>
                    <form method="POST" action="{{ route('admin.applications.requestDocuments', $application->id) }}">
                        @csrf
                        <div class="space-y-2 mb-4">
                            @foreach($application->documents->whereNotNull('document_type_id') as $doc)
                                <label class="flex items-center gap-2 p-2 border border-gray-200 rounded text-xs cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="flagged_document_ids[]" value="{{ $doc->document_type_id }}" class="rounded text-emerald-600">
                                    <span>{{ $doc->documentType?->name ?? 'Document' }}</span>
                                </label>
                            @endforeach
                            @foreach($application->documents->whereNotNull('cert_name') as $doc)
                                <label class="flex items-center gap-2 p-2 border border-gray-200 rounded text-xs cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="flagged_cert_names[]" value="{{ $doc->cert_name }}" class="rounded text-emerald-600">
                                    <span>{{ $doc->cert_name }} Certificate</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Additional Note</label>
                            <textarea name="review_notes" rows="2" placeholder="e.g. Please re-upload a clear copy of your National ID..." class="w-full border border-gray-300 rounded-lg p-2 text-xs"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeRequestDocsModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" id="btn-send-request" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-xs font-medium hover:bg-amber-700">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openShortlistModal() { document.getElementById('shortlistModal').classList.remove('hidden'); document.getElementById('shortlistModal').classList.add('flex'); }
                function closeShortlistModal() { document.getElementById('shortlistModal').classList.remove('flex'); document.getElementById('shortlistModal').classList.add('hidden'); }
                function openInterviewModal() { document.getElementById('interviewModal').classList.remove('hidden'); document.getElementById('interviewModal').classList.add('flex'); }
                function closeInterviewModal() { document.getElementById('interviewModal').classList.remove('flex'); document.getElementById('interviewModal').classList.add('hidden'); }
                function openApproveModal()  { document.getElementById('approveModal').classList.remove('hidden'); document.getElementById('approveModal').classList.add('flex'); }
                function closeApproveModal() { document.getElementById('approveModal').classList.remove('flex'); document.getElementById('approveModal').classList.add('hidden'); }
                function openRejectModal()  { document.getElementById('rejectModal').classList.remove('hidden'); document.getElementById('rejectModal').classList.add('flex'); }
                function closeRejectModal() { document.getElementById('rejectModal').classList.remove('flex'); document.getElementById('rejectModal').classList.add('hidden'); }
                function openRequestDocsModal()  { document.getElementById('requestDocsModal').classList.remove('hidden'); document.getElementById('requestDocsModal').classList.add('flex'); }
                function closeRequestDocsModal() { document.getElementById('requestDocsModal').classList.remove('flex'); document.getElementById('requestDocsModal').classList.add('hidden'); }
            </script>
        </div>
    </div>
@endsection
