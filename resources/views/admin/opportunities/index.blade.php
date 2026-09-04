@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Advertised Internship Opportunities</h1>
                <p class="text-slate-500 text-xs mt-0.5">Publish and manage specific advertised vacancy postings.</p>
            </div>
            <a href="{{ route('admin.opportunities.create') }}" class="px-5 py-2.5 rounded-xl bg-blue-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-800 transition">
                + Create Opportunity
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            @include('admin.partials.sidebar')

            <div class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase bg-slate-50">
                                <th class="py-3 px-4">Opportunity Title</th>
                                <th class="py-3 px-4">Department</th>
                                <th class="py-3 px-4">Positions</th>
                                <th class="py-3 px-4">Duration</th>
                                <th class="py-3 px-4">Closing Date</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($opportunities as $opp)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $opp->title }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-blue-900">{{ $opp->department->name ?? '—' }}</td>
                                    <td class="py-3.5 px-4">{{ $opp->positions_count }}</td>
                                    <td class="py-3.5 px-4">{{ $opp->duration_months }} Months</td>
                                    <td class="py-3.5 px-4">{{ optional($opp->closing_date)->format('d M Y') ?? 'No deadline' }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full font-bold uppercase text-[10px] {{ $opp->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $opp->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('admin.opportunities.edit', $opp->id) }}" class="font-bold text-blue-900 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $opportunities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
