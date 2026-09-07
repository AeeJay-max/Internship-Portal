@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block mb-1">Ministry Administration</span>
                <h1 class="text-2xl font-black text-slate-900">MoSRAC Internship Management</h1>
                <p class="text-slate-500 text-xs mt-0.5">Ministry of Sport, Recreation, Arts & Culture — Overview & Analytics</p>
            </div>
            <div class="text-xs font-bold text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">{{ now()->format('l, d F Y') }}</div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            @include('admin.partials.sidebar')

            <div class="flex-1 space-y-6">

                {{-- STATS GRID --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Submitted</span>
                        <div class="text-2xl font-black text-emerald-800 mt-1">{{ $stats['submitted'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Under Review</span>
                        <div class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['under_review'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Shortlisted</span>
                        <div class="text-2xl font-black text-teal-800 mt-1">{{ $stats['shortlisted'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Placement Pending</span>
                        <div class="text-2xl font-black text-amber-700 mt-1">{{ $stats['placement_pending'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Placed</span>
                        <div class="text-2xl font-black text-emerald-900 mt-1">{{ $stats['placed'] }}</div>
                    </div>
                </div>

                {{-- 1-MONTH CONTRACT EXPIRY NOTIFICATION FOR ADMIN --}}
                @php
                    $expiringPlacements = \App\Models\InternshipPlacement::with(['application.user', 'department'])
                        ->whereNotNull('end_date')
                        ->where('end_date', '>=', now())
                        ->where('end_date', '<=', now()->addDays(30))
                        ->get();
                @endphp

                @if($expiringPlacements->isNotEmpty())
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-extrabold text-lg">
                                    ⏳
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-sm">Contract Expiry Warning — 1 Month Remaining</h3>
                                    <p class="text-xs text-amber-800">The following {{ $expiringPlacements->count() }} intern placement contract(s) end in 30 days or less:</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.placements.index') }}" class="px-3.5 py-1.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs rounded-lg uppercase tracking-wider shadow">
                                View Placements
                            </a>
                        </div>

                        <div class="divide-y divide-amber-200/60 pt-2">
                            @foreach($expiringPlacements as $ep)
                                <div class="py-2.5 flex items-center justify-between text-xs">
                                    <div>
                                        <strong class="text-slate-900 font-extrabold">{{ $ep->application->user->name ?? 'Intern' }}</strong>
                                        <span class="text-slate-500 font-medium">({{ $ep->department->name ?? 'Ministry' }})</span>
                                        <span class="block text-[11px] text-amber-900">Final Date: <strong>{{ $ep->end_date->format('d M Y') }}</strong></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 bg-red-600 text-white font-extrabold text-xs rounded-full shadow-sm">
                                            {{ $ep->days_remaining }} Days Countdown
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ANALYTICS GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Applications by Department --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-black text-slate-900 text-sm">Applications by Preferred Department</h3>
                        <div class="space-y-3">
                            @foreach($byDepartment->take(6) as $dept)
                                <div>
                                    <div class="flex justify-between text-xs font-semibold mb-1">
                                        <span class="text-slate-700">{{ $dept->name }}</span>
                                        <span class="text-slate-900 font-bold">{{ $dept->preferences_count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        <div class="bg-emerald-700 h-2 rounded-full" style="width: {{ $stats['total_applications'] > 0 ? ($dept->preferences_count / $stats['total_applications']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Placements by Department --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-black text-slate-900 text-sm">Placements by Department</h3>
                        <div class="space-y-3">
                            @foreach($placementsByDepartment->take(6) as $dept)
                                <div>
                                    <div class="flex justify-between text-xs font-semibold mb-1">
                                        <span class="text-slate-700">{{ $dept->name }}</span>
                                        <span class="text-emerald-800 font-bold">{{ $dept->placements_count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $stats['placed'] > 0 ? ($dept->placements_count / max(1, $stats['placed'])) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RECENT APPLICATIONS TABLE --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-4">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-black text-slate-900 text-base">Recent Applications</h3>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 underline">View All &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-6">Reference</th>
                                    <th class="py-3 px-6">Applicant</th>
                                    <th class="py-3 px-6">Preferred Dept</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                                @foreach($recentApplications as $app)
                                    @php $badge = $app->statusBadge(); @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-3.5 px-6 font-black text-slate-900">{{ $app->reference_number }}</td>
                                        <td class="py-3.5 px-6 font-semibold">{{ $app->user->name ?? 'Applicant' }}</td>
                                        <td class="py-3.5 px-6">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                        <td class="py-3.5 px-6">
                                            <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <td class="py-3.5 px-6 text-right">
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="font-bold text-emerald-700 hover:text-emerald-900 underline">Review &rarr;</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

