@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Admission Cycles</h1>
                            <p class="text-gray-500 text-sm mt-1">Manage application intake periods per program</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
                            <a href="{{ route('admin.cycles.create') }}"
                               class="px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                               style="background: #011C3E;">
                                + New Cycle
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
                    @endif

                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Program</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Intake</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Opens</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deadline</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Capacity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Applications</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse($cycles as $cycle)
                                @php
                                    $now    = now();
                                    $open   = $now->between($cycle->starts_at, $cycle->deadline_at);
                                    $past   = $now->isAfter($cycle->deadline_at);
                                    $future = $now->isBefore($cycle->starts_at);
                                    $appCount = $cycle->applications()->count();
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $cycle->program->name }}</div>
                                        <div class="text-xs text-gray-400 capitalize">{{ $cycle->program->degree_level }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-700">{{ $cycle->intake_name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($cycle->starts_at)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($cycle->deadline_at)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $cycle->capacity ?? '∞' }}</td>
                                    <td class="px-6 py-4">
                                        @if($open)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Open</span>
                                        @elseif($future)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Upcoming</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Closed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-700">{{ $appCount }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.cycles.edit', $cycle) }}"
                                               class="text-sm font-medium text-blue-600 hover:underline">Edit</a>
                                            @if($appCount === 0)
                                                <form method="POST" action="{{ route('admin.cycles.destroy', $cycle) }}"
                                                      onsubmit="return confirm('Delete this cycle?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-500 hover:underline">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-300">Has applications</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">No admission cycles yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $cycles->links() }}
                        </div>
                    </div>

                </div>{{-- end main content --}}
            </div>{{-- end flex --}}
        </div>
    </div>
@endsection
