@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 md:px-6 space-y-8">

        {{-- Welcome Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block mb-1">Applicant Portal</span>
                <h1 class="text-3xl font-black text-slate-900">My Internship Applications</h1>
                <p class="text-slate-600 text-xs mt-0.5">Ministry of Sport, Recreation, Arts & Culture — Government of Zimbabwe</p>
            </div>
            <a href="{{ route('internships.index') }}" class="px-6 py-3 rounded-xl bg-[#005A2B] hover:bg-[#00421F] text-white font-extrabold text-xs uppercase tracking-wider transition shadow-md inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>New Internship Application</span>
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @php
            $expiringPlacement = null;
            foreach($applications as $app) {
                if ($app->placement && $app->placement->isExpiringSoon()) {
                    $expiringPlacement = $app->placement;
                    break;
                }
            }
        @endphp

        {{-- 1-MONTH CONTRACT EXPIRY NOTIFICATION BANNER --}}
        @if($expiringPlacement)
            <div class="p-6 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-600 to-red-600 text-white shadow-xl border-2 border-amber-300 relative overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-black shrink-0">
                            ⏳
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-white text-red-700 rounded-full text-[10px] font-extrabold uppercase tracking-widest mb-1">Contract Ending Soon (1 Month or Less)</span>
                            <h2 class="text-xl font-extrabold text-white">Attachment Contract Expiry Countdown</h2>
                            <p class="text-xs text-amber-100 mt-1">
                                Your internship contract at <strong>{{ $expiringPlacement->department->name ?? 'Ministry' }}</strong> ends on <strong>{{ $expiringPlacement->end_date ? $expiringPlacement->end_date->format('d M Y') : 'Final Date' }}</strong>.
                            </p>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-center border border-white/20 shrink-0 min-w-[140px]">
                        <span class="block text-2xl font-black text-white leading-none">
                            {{ $expiringPlacement->days_remaining }} Days
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-200">Remaining to Final Date</span>
                    </div>
                </div>
                <p class="text-[11px] text-amber-100/90 mt-3 pt-3 border-t border-white/20">
                    📌 <strong>Notice:</strong> Please ensure all supervisor clearance forms, logbooks, and final attachment assessment reports are completed before <strong>{{ $expiringPlacement->end_date ? $expiringPlacement->end_date->format('d M Y') : 'Final Date' }}</strong>.
                </p>
        @endif

        {{-- INTERVIEW SCHEDULED BANNER --}}
        @php $interviewApp = $applications->firstWhere('status', 'interview_required'); @endphp
        @if($interviewApp)
            <div class="p-8 rounded-2xl bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-950 text-white shadow-xl space-y-5 border border-blue-600/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-blue-700/60 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-extrabold text-white text-2xl shadow-inner">📅</span>
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-400 text-slate-950">Action Required: Interview Scheduled</span>
                            <h2 class="text-2xl font-extrabold text-white mt-1">Ministry Internship Interview Scheduled</h2>
                            <p class="text-xs text-blue-200">Application Ref: <strong>{{ $interviewApp->reference_number }}</strong> — Ministry of Sport, Recreation, Arts & Culture</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-blue-500/30 text-blue-200 border border-blue-400/40 uppercase tracking-wider self-start md:self-auto">
                        Interview Scheduled
                    </span>
                </div>

                {{-- Scheduled Date, Time & Venue Card --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Scheduled Date</span>
                        <p class="text-base font-extrabold text-white flex items-center gap-2">
                            <span>📆</span>
                            {{ $interviewApp->interview_date ? $interviewApp->interview_date->format('l, d F Y') : 'Date to be confirmed' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Scheduled Time</span>
                        <p class="text-base font-extrabold text-white flex items-center gap-2">
                            <span>⏰</span>
                            {{ $interviewApp->interview_time ? \Carbon\Carbon::parse($interviewApp->interview_time)->format('g:i A') : 'Time to be confirmed' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-950/80 border border-blue-700/50">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-300 block mb-1">Venue / Location</span>
                        <p class="text-xs font-bold text-white leading-snug flex items-start gap-1.5">
                            <span class="shrink-0 mt-0.5">📍</span>
                            <span>{{ $interviewApp->interview_location ?? 'Chinengundu Mashayamombe Building, 95 Cnr N.Mandela & S. V. Muzenda Street, Harare' }}</span>
                        </p>
                    </div>
                </div>

                @if($interviewApp->review_notes)
                    <div class="p-4 rounded-xl bg-slate-900/90 border border-blue-800/60 text-xs text-blue-100 space-y-1.5">
                        <span class="font-bold uppercase text-[10px] tracking-wider text-amber-300 block">Ministry Instructions:</span>
                        <div class="whitespace-pre-line leading-relaxed font-sans">{{ $interviewApp->review_notes }}</div>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 pt-1">
                    <a href="{{ route('application.show', $interviewApp->id) }}" class="px-6 py-3 rounded-xl bg-white text-slate-950 font-extrabold text-xs hover:bg-blue-50 shadow transition inline-flex items-center gap-1.5">
                        <span>View Application Details & Venue Map</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- APPLICATION APPROVED / ONBOARDING BANNER --}}
        @php $approvedApp = $applications->first(fn($a) => in_array($a->status, ['placement_pending', 'approved', 'placed'])); @endphp
        @if($approvedApp && !$approvedApp->isPlaced())
            <div class="p-8 rounded-2xl bg-gradient-to-br from-emerald-900 via-emerald-950 to-slate-950 text-white shadow-xl space-y-5 border border-emerald-700/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-emerald-700/60 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-12 h-12 rounded-2xl bg-emerald-700 flex items-center justify-center font-extrabold text-white text-2xl shadow-inner">🎉</span>
                        <div>
                            <h2 class="text-2xl font-extrabold text-white">Application Approved!</h2>
                            <p class="text-xs text-emerald-200">Reference: <strong>{{ $approvedApp->reference_number }}</strong> — Ministry of Sport, Recreation, Arts & Culture</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-400 text-slate-950 shadow-sm uppercase tracking-wider self-start md:self-auto">
                        Placement Pending
                    </span>
                </div>

                @if($approvedApp->review_notes)
                    <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-700/40 text-xs text-emerald-100 space-y-2">
                        <span class="font-bold uppercase text-[10px] tracking-wider text-amber-300 block">Acceptor Instructions & Required Documents to Bring / Attach:</span>
                        <div class="whitespace-pre-line leading-relaxed font-sans">{{ $approvedApp->review_notes }}</div>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 pt-1">
                    <a href="{{ route('application.show', $approvedApp->id) }}" class="px-6 py-3 rounded-xl bg-white text-emerald-950 font-extrabold text-xs hover:bg-emerald-50 shadow transition inline-flex items-center gap-1.5">
                        <span>View Acceptance Details & Attach Onboarding Documents</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- PLACEMENT INFORMATION BANNER (If Placed) --}}
        @if($latestApplication && $latestApplication->isPlaced() && $latestApplication->placement)
            @php $pl = $latestApplication->placement; @endphp
            <div class="p-8 rounded-2xl bg-emerald-900 text-white shadow-xl space-y-4 border border-emerald-700">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-black text-lg shadow-sm">✓</span>
                    <div>
                        <h2 class="text-xl font-extrabold">Congratulations! You Have Been Placed</h2>
                        <p class="text-xs text-emerald-200">Application Reference: {{ $latestApplication->reference_number }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-emerald-700/60 text-xs">
                    <div>
                        <span class="text-amber-300 uppercase font-bold block mb-1">Assigned Department</span>
                        <p class="font-bold text-sm text-white">{{ $pl->department->name ?? 'Ministry' }}</p>
                        @if($pl->division_unit)<p class="text-emerald-200">{{ $pl->division_unit }}</p>@endif
                    </div>
                    <div>
                        <span class="text-amber-300 uppercase font-bold block mb-1">Supervisor Details</span>
                        <p class="font-bold text-sm text-white">{{ $pl->supervisor_name }}</p>
                        <p class="text-emerald-200">{{ $pl->supervisor_email }} | {{ $pl->supervisor_phone }}</p>
                    </div>
                    <div>
                        <span class="text-amber-300 uppercase font-bold block mb-1">Internship Duration</span>
                        <p class="font-bold text-sm text-white">{{ optional($pl->start_date)->format('d M Y') }} – {{ optional($pl->end_date)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('application.show', $latestApplication->id) }}" class="inline-block px-5 py-2.5 rounded-xl bg-white text-emerald-950 font-bold text-xs hover:bg-emerald-50 transition shadow">
                        View Complete Placement Details & Reporting Instructions &rarr;
                    </a>
                </div>
            </div>
        @endif

        {{-- URGENT REUPLOAD BANNER --}}
        @php $docsReq = $applications->firstWhere('status', 'documents_requested'); @endphp
        @if($docsReq)
            <div class="p-6 rounded-2xl bg-amber-50 border-2 border-amber-300 shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="font-extrabold text-amber-950 text-base">⚠️ Additional Documents Required</h3>
                    <p class="text-xs text-amber-900">
                        Reference <strong>{{ $docsReq->reference_number }}</strong> requires document re-uploads.
                        @if($docsReq->review_notes) Ministry note: <em>"{{ $docsReq->review_notes }}"</em> @endif
                    </p>
                </div>
                <a href="{{ route('application.show', $docsReq->id) }}" class="px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition shadow">
                    Upload Documents Now &rarr;
                </a>
            </div>
        @endif

        {{-- ACTIVE DRAFT CARD --}}
        @if($draft)
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-200 uppercase">Draft Application</span>
                        <h2 class="text-xl font-black text-slate-900 mt-2">{{ $draft->reference_number }}</h2>
                        <p class="text-xs text-slate-500 font-medium">Preferred Department: {{ $draft->preference->preferredDepartment->name ?? 'Not selected' }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('application.personal') }}" class="px-6 py-3 rounded-xl bg-[#005A2B] hover:bg-[#00421F] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                            Continue Application &rarr;
                        </a>
                        <button onclick="openDeleteModal()" class="px-4 py-3 rounded-xl border border-rose-300 text-rose-600 font-bold text-xs uppercase hover:bg-rose-50 transition">
                            Delete Draft
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-[#005A2B] h-2 rounded-full transition-all duration-500" style="width: {{ $draft->completion_percentage }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 text-right font-medium">{{ $draft->completion_percentage }}% Completed</p>
                </div>
            </div>
        @endif

        {{-- SUBMITTED APPLICATIONS HISTORY --}}
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-xl font-black text-slate-900">Application History</h2>

            @if($applications->isEmpty() && !$draft)
                <div class="text-center py-10 space-y-3">
                    <p class="text-sm text-slate-500 font-medium">You have no active or submitted internship applications.</p>
                    <a href="{{ route('application.selectType') }}" class="inline-block px-6 py-3 rounded-xl bg-[#005A2B] hover:bg-[#00421F] text-white font-bold text-xs uppercase tracking-wider transition shadow">
                        Submit General Internship Application
                    </a>
                </div>
            @elseif(!$applications->isEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold text-slate-500 uppercase bg-slate-50">
                                <th class="py-3 px-4">Reference</th>
                                <th class="py-3 px-4">Preferred Department</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Submitted Date</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700 font-medium">
                            @foreach($applications as $app)
                                @php $badge = $app->statusBadge(); @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-4 px-4 font-black text-slate-900">{{ $app->reference_number }}</td>
                                    <td class="py-4 px-4">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-4">{{ optional($app->submitted_at)->format('d M Y') }}</td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('application.show', $app->id) }}" class="font-bold text-[#005A2B] hover:text-[#00421F] underline">
                                            View Details &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- DELETE MODAL --}}
@if($draft)
    <div id="deleteModal" class="fixed inset-0 bg-slate-900/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full space-y-4">
            <h3 class="text-lg font-bold text-slate-900">Delete Draft Application?</h3>
            <p class="text-xs text-slate-600">This action will remove all saved progress for this draft.</p>
            <div class="flex justify-end gap-3 pt-4">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50">Cancel</button>
                <form method="POST" action="{{ route('application.destroyDraft', $draft->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openDeleteModal()  { document.getElementById('deleteModal').classList.remove('hidden'); }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }
    </script>
@endif
@endsection

