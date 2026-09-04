@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Ministry Internship Administration</h1>
                <p class="text-slate-500 text-xs mt-0.5">Ministry of Sport, Recreation, Arts & Culture — Overview & Analytics</p>
            </div>
            <div class="text-xs font-semibold text-slate-400">{{ now()->format('l, d F Y') }}</div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            @include('admin.partials.sidebar')

            <div class="flex-1 space-y-6">

                {{-- STATS GRID --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase">Submitted</span>
                        <div class="text-2xl font-extrabold text-blue-900 mt-1">{{ $stats['submitted'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase">Under Review</span>
                        <div class="text-2xl font-extrabold text-purple-900 mt-1">{{ $stats['under_review'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase">Shortlisted</span>
                        <div class="text-2xl font-extrabold text-teal-900 mt-1">{{ $stats['shortlisted'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase">Pending Placement</span>
                        <div class="text-2xl font-extrabold text-amber-900 mt-1">{{ $stats['placement_pending'] }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase">Placed</span>
                        <div class="text-2xl font-extrabold text-emerald-900 mt-1">{{ $stats['placed'] }}</div>
                    </div>
                </div>

                {{-- ANALYTICS GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Applications by Department --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-bold text-slate-900 text-sm">Applications by Preferred Department</h3>
                        <div class="space-y-3">
                            @foreach($byDepartment->take(6) as $dept)
                                <div>
                                    <div class="flex justify-between text-xs font-semibold mb-1">
                                        <span class="text-slate-700">{{ $dept->name }}</span>
                                        <span class="text-slate-900 font-bold">{{ $dept->preferences_count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        <div class="bg-blue-900 h-2 rounded-full" style="width: {{ $stats['total_applications'] > 0 ? ($dept->preferences_count / $stats['total_applications']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Placements by Department --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-bold text-slate-900 text-sm">Placements by Department</h3>
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
                        <h3 class="font-bold text-slate-900 text-base">Recent Applications</h3>
                        <a href="{{ route('admin.applications.index') }}" class="text-xs font-bold text-blue-900 hover:underline">View All &rarr;</a>
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
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($recentApplications as $app)
                                    @php $badge = $app->statusBadge(); @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3.5 px-6 font-bold text-slate-900">{{ $app->reference_number }}</td>
                                        <td class="py-3.5 px-6 font-semibold">{{ $app->user->name ?? 'Applicant' }}</td>
                                        <td class="py-3.5 px-6">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                        <td class="py-3.5 px-6">
                                            <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <td class="py-3.5 px-6 text-right">
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="font-bold text-blue-900 hover:underline">Review &rarr;</a>
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
