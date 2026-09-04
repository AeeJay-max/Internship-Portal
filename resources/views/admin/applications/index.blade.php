@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Internship Applications</h1>
                <p class="text-slate-500 text-xs mt-0.5">Review, shortlist, request documents, approve, and place candidates.</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            @include('admin.partials.sidebar')

            <div class="flex-1 min-w-0 space-y-6">

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- STATS STRIP --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach([
                        ['label' => 'Total', 'value' => $stats['total'], 'status' => ''],
                        ['label' => 'Submitted', 'value' => $stats['submitted'], 'status' => 'submitted'],
                        ['label' => 'Under Review', 'value' => $stats['under_review'], 'status' => 'under_review'],
                        ['label' => 'Shortlisted', 'value' => $stats['shortlisted'], 'status' => 'shortlisted'],
                        ['label' => 'Placement Pending', 'value' => $stats['placement_pending'], 'status' => 'placement_pending'],
                    ] as $s)
                        <a href="{{ $s['status'] ? route('admin.applications.index', ['status' => $s['status']]) : route('admin.applications.index') }}"
                           class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center hover:shadow transition">
                            <div class="text-xl font-extrabold text-blue-900">{{ $s['value'] }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase mt-1">{{ $s['label'] }}</div>
                        </a>
                    @endforeach
                </div>

                {{-- FILTER FORM --}}
                <form method="GET" action="{{ route('admin.applications.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center text-xs">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference, name or email..." class="flex-1 w-full rounded-xl border-slate-300 text-xs">
                    <select name="status" class="w-full md:w-48 rounded-xl border-slate-300 text-xs">
                        <option value="">All Statuses</option>
                        <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                        <option value="under_review" @selected(request('status') === 'under_review')>Under Review</option>
                        <option value="documents_requested" @selected(request('status') === 'documents_requested')>Documents Required</option>
                        <option value="shortlisted" @selected(request('status') === 'shortlisted')>Shortlisted</option>
                        <option value="interview_required" @selected(request('status') === 'interview_required')>Interview Required</option>
                        <option value="placement_pending" @selected(request('status') === 'placement_pending')>Placement Pending</option>
                        <option value="placed" @selected(request('status') === 'placed')>Placed</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-900 text-white font-bold uppercase">Filter</button>
                    <a href="{{ route('admin.applications.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 font-bold">Reset</a>
                </form>

                {{-- TABLE --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase bg-slate-50">
                                    <th class="py-3 px-4">Reference</th>
                                    <th class="py-3 px-4">Applicant</th>
                                    <th class="py-3 px-4">Preferred Dept</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Submitted</th>
                                    <th class="py-3 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($applications as $app)
                                    @php $badge = $app->statusBadge(); @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3.5 px-4 font-bold text-slate-900">{{ $app->reference_number }}</td>
                                        <td class="py-3.5 px-4 font-semibold">{{ $app->user->name ?? 'Applicant' }}</td>
                                        <td class="py-3.5 px-4">{{ $app->preference->preferredDepartment->name ?? 'General' }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="px-2.5 py-1 rounded-full font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <td class="py-3.5 px-4">{{ optional($app->submitted_at)->format('d M Y') }}</td>
                                        <td class="py-3.5 px-4 text-right">
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="font-bold text-blue-900 hover:underline">Review &rarr;</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-4 text-center text-slate-400">No applications matching filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-slate-100">
                        {{ $applications->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
