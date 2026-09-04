@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 md:px-6 space-y-8">

        {{-- Welcome Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">My Internship Portal</h1>
                <p class="text-slate-600 text-sm mt-1">Ministry of Sport, Recreation, Arts & Culture</p>
            </div>
            <a href="{{ route('application.selectType') }}" class="px-6 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800 transition shadow">
                + New Internship Application
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        {{-- PLACEMENT INFORMATION BANNER (If Placed) --}}
        @if($latestApplication && $latestApplication->isPlaced() && $latestApplication->placement)
            @php $pl = $latestApplication->placement; @endphp
            <div class="p-8 rounded-2xl bg-emerald-900 text-white shadow-xl space-y-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-emerald-700 flex items-center justify-center font-bold text-white text-lg">✓</span>
                    <div>
                        <h2 class="text-xl font-extrabold">Congratulations! You Have Been Placed</h2>
                        <p class="text-xs text-emerald-200">Application Reference: {{ $latestApplication->reference_number }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-emerald-700/60 text-xs">
                    <div>
                        <span class="text-emerald-300 uppercase font-semibold block mb-1">Assigned Department</span>
                        <p class="font-bold text-sm text-white">{{ $pl->department->name ?? 'Ministry' }}</p>
                        @if($pl->division_unit)<p class="text-emerald-200">{{ $pl->division_unit }}</p>@endif
                    </div>
                    <div>
                        <span class="text-emerald-300 uppercase font-semibold block mb-1">Supervisor</span>
                        <p class="font-bold text-sm text-white">{{ $pl->supervisor_name }}</p>
                        <p class="text-emerald-200">{{ $pl->supervisor_email }} | {{ $pl->supervisor_phone }}</p>
                    </div>
                    <div>
                        <span class="text-emerald-300 uppercase font-semibold block mb-1">Internship Dates</span>
                        <p class="font-bold text-sm text-white">{{ optional($pl->start_date)->format('d M Y') }} – {{ optional($pl->end_date)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('application.show', $latestApplication->id) }}" class="inline-block px-5 py-2.5 rounded-lg bg-white text-emerald-950 font-bold text-xs hover:bg-emerald-50">
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
                    <h3 class="font-extrabold text-amber-900 text-base">⚠️ Additional Documents Required</h3>
                    <p class="text-xs text-amber-800">
                        Reference <strong>{{ $docsReq->reference_number }}</strong> requires document re-uploads.
                        @if($docsReq->review_notes) Admin note: <em>"{{ $docsReq->review_notes }}"</em> @endif
                    </p>
                </div>
                <a href="{{ route('application.show', $docsReq->id) }}" class="px-5 py-2.5 rounded-xl bg-amber-700 text-white font-bold text-xs hover:bg-amber-800 transition">
                    Upload Documents Now &rarr;
                </a>
            </div>
        @endif

        {{-- ACTIVE DRAFT CARD --}}
        @if($draft)
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded bg-amber-100 text-amber-800 uppercase">Draft Application</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-2">{{ $draft->reference_number }}</h2>
                        <p class="text-xs text-slate-500">Preferred Department: {{ $draft->preference->preferredDepartment->name ?? 'Not selected' }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('application.personal') }}" class="px-6 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800">
                            Continue Application
                        </a>
                        <button onclick="openDeleteModal()" class="px-4 py-3 rounded-xl border border-rose-300 text-rose-600 font-bold text-xs uppercase hover:bg-rose-50">
                            Delete Draft
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-900 h-2 rounded-full transition-all duration-500" style="width: {{ $draft->completion_percentage }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 text-right">{{ $draft->completion_percentage }}% Completed</p>
                </div>
            </div>
        @endif

        {{-- SUBMITTED APPLICATIONS HISTORY --}}
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-xl font-extrabold text-slate-900">My Internship Applications</h2>

            @if($applications->isEmpty() && !$draft)
                <div class="text-center py-10 space-y-3">
                    <p class="text-sm text-slate-500">You have no active or submitted internship applications.</p>
                    <a href="{{ route('application.selectType') }}" class="inline-block px-6 py-3 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider">
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
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @foreach($applications as $app)
                                @php $badge = $app->statusBadge(); @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="py-4 px-4 font-bold text-slate-900">{{ $app->reference_number }}</td>
                                    <td class="py-4 px-4">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-4">{{ optional($app->submitted_at)->format('d M Y') }}</td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('application.show', $app->id) }}" class="font-bold text-blue-900 hover:underline">
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
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-bold">Cancel</button>
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
