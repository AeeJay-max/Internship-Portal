@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Applicants</h1>
                            <p class="text-gray-500 text-sm mt-1">All registered applicants — track activity and re-engage inactive users</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif

                    {{-- ── Stat cards ── --}}
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        @php
                            $statCards = [
                                ['label' => 'Total Registered', 'value' => $stats['total'],           'filter' => 'all',      'color' => '#1d4ed8'],
                                ['label' => 'Active Accounts',  'value' => $stats['active'],          'filter' => 'active',   'color' => '#15803d'],
                                ['label' => 'Deleted Accounts', 'value' => $stats['deleted'],         'filter' => 'deleted',  'color' => '#b91c1c'],
                                ['label' => 'Have Draft',        'value' => $stats['with_draft'],     'filter' => 'draft',    'color' => '#c2410c'],
                                ['label' => 'Inactive 7+ Days', 'value' => $stats['inactive_draft'], 'filter' => 'inactive', 'color' => '#7e22ce'],
                            ];
                        @endphp
                        @foreach($statCards as $s)
                            <a href="{{ route('admin.applicants.index', ['filter' => $s['filter']]) }}"
                               class="bg-white rounded-xl border p-4 transition hover:shadow-md
                                  {{ $filter === $s['filter'] && !$department ? 'ring-1' : 'border-gray-100' }}"
                               style="{{ $filter === $s['filter'] && !$department ? 'border-color:'.$s['color'].'; ring-color:'.$s['color'] : '' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-3xl font-bold" style="color:{{ $s['color'] }}">{{ $s['value'] }}</p>
                                </div>
                                <p class="text-xs text-gray-400">{{ $s['label'] }}</p>
                            </a>
                        @endforeach
                    </div>

                    {{-- ── Filter bar ── --}}
                    <form method="GET" action="{{ route('admin.applicants.index') }}"
                          class="bg-white rounded-xl shadow border border-gray-100 p-4 mb-6">
                        <div class="flex flex-wrap gap-3 items-end">

                            {{-- Search --}}
                            <div class="flex-1 min-w-48">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Search</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="search" value="{{ $search }}"
                                           placeholder="Name or email..."
                                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                                </div>
                            </div>

                            {{-- Department dropdown --}}
                            <div class="min-w-44">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Department</label>
                                <select name="department"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $department == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status dropdown --}}
                            <div class="min-w-36">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Status</label>
                                <select name="filter"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                                    <option value="active"   {{ $filter === 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="draft"    {{ $filter === 'draft'    ? 'selected' : '' }}>Have Draft</option>
                                    <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Inactive 7+ Days</option>
                                    <option value="deleted"  {{ $filter === 'deleted'  ? 'selected' : '' }}>Deleted</option>
                                    <option value="all"      {{ $filter === 'all'      ? 'selected' : '' }}>All</option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-2">
                                <button type="submit"
                                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition hover:shadow-md"
                                        style="background:#011C3E;">
                                    Filter
                                </button>
                                @if($search || $department || $filter !== 'active')
                                    <a href="{{ route('admin.applicants.index') }}"
                                       class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-500">
                                        Clear
                                    </a>
                                @endif
                            </div>

                        </div>

                        {{-- Active filter chips --}}
                        @if($search || $department || $filter !== 'active')
                            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-400 self-center">Active filters:</span>
                                @if($search)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                                    Search: "{{ $search }}"
                                    <a href="{{ route('admin.applicants.index', array_merge(request()->except('search','page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                                @if($department)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-purple-50 text-purple-700">
                                    {{ $departments->find($department)?->name }}
                                    <a href="{{ route('admin.applicants.index', array_merge(request()->except('department','page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                                @if($filter && $filter !== 'active')
                                    @php $filterLabels = ['all'=>'All','draft'=>'Have Draft','inactive'=>'Inactive 7+ Days','deleted'=>'Deleted']; @endphp
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-orange-50 text-orange-700">
                                    {{ $filterLabels[$filter] ?? $filter }}
                                    <a href="{{ route('admin.applicants.index', array_merge(request()->except('filter','page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                            </div>
                        @endif

                    </form>

                    {{-- ── Table ── --}}
                    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Applicant</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Account</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Application</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Progress</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Last Activity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                                @php
                                    $latestApp    = $user->applications->first();
                                    $isDraft      = $latestApp && $latestApp->status === 'draft';
                                    $isDeleted    = $user->trashed();
                                    $lastActivity = $latestApp ? $latestApp->updated_at : $user->created_at;
                                    $minutesSince = (int) $lastActivity->diffInMinutes(now());
                                    $hoursSince   = (int) $lastActivity->diffInHours(now());
                                    $daysSince    = (int) $lastActivity->diffInDays(now());

                                    if ($minutesSince < 60) {
                                        $activityLabel = $minutesSince <= 1 ? 'Just now' : $minutesSince . ' min ago';
                                    } elseif ($hoursSince < 24) {
                                        $activityLabel = $hoursSince === 1 ? '1 hour ago' : $hoursSince . ' hours ago';
                                    } elseif ($daysSince === 1) {
                                        $activityLabel = 'Yesterday';
                                    } else {
                                        $activityLabel = $daysSince . ' days ago';
                                    }

                                    $isInactive = $isDraft && $daysSince >= 7;
                                @endphp
                                <tr class="hover:bg-gray-50 transition {{ $isDeleted ? 'opacity-60' : '' }} {{ $isInactive ? 'bg-purple-50/40' : '' }}">

                                    {{-- Applicant --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                                 style="background:{{ $isDeleted ? '#ef4444' : 'linear-gradient(135deg,#011C3E,#611818)' }};">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800 text-sm flex items-center gap-1.5 flex-wrap">
                                                    {{ $user->name }}
                                                    @if($isDeleted)
                                                        <span class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full font-semibold">Deleted</span>
                                                    @endif
                                                    @if($isInactive)
                                                        <span class="text-xs bg-purple-100 text-purple-600 px-1.5 py-0.5 rounded-full font-semibold">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Account --}}
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-500">Registered {{ $user->created_at->format('d M Y') }}</div>
                                        @if($user->email_verified_at)
                                            <div class="text-xs text-green-600 font-medium mt-0.5">✓ Email verified</div>
                                        @else
                                            <div class="text-xs text-orange-500 font-medium mt-0.5">⚠ Not verified</div>
                                        @endif
                                        @if($isDeleted)
                                            <div class="text-xs text-red-500 mt-0.5">Deleted {{ $user->deleted_at->format('d M Y') }}</div>
                                        @endif
                                    </td>

                                    {{-- Application --}}
                                    <td class="px-4 py-3">
                                        @if($latestApp)
                                            @php $badge = $latestApp->statusBadge(); @endphp
                                            @php
                                                $appLevel = $latestApp->program?->degree_level;
                                                $lc = ['bachelor'=>['BSc','#eff6ff','#1d4ed8'],'master'=>['MSc','#f5f3ff','#7c3aed'],'phd'=>['PhD','#fef3c7','#b45309']];
                                                $lb = $lc[$appLevel] ?? null;
                                            @endphp
                                            <div class="flex items-center gap-1.5 mb-1">
                                                @if($lb)
                                                    <span class="text-xs font-bold px-1.5 py-0.5 rounded-full shrink-0"
                                                          style="background:{{ $lb[1] }};color:{{ $lb[2] }}">{{ $lb[0] }}</span>
                                                @endif
                                                <span class="text-xs font-medium text-gray-700">{{ $latestApp->program?->name ?? '—' }}</span>
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold inline-block {{ $badge['class'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                        @else
                                            <span class="text-xs text-gray-300">No application</span>
                                        @endif
                                    </td>

                                    {{-- Progress --}}
                                    <td class="px-4 py-3">
                                        @if($isDraft)
                                            <div class="w-32">
                                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                    <span>{{ $latestApp->completion_percentage }}%</span>
                                                    <span>Step {{ $latestApp->current_step }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="h-1.5 rounded-full"
                                                         style="width:{{ $latestApp->completion_percentage }}%; background:#611818;"></div>
                                                </div>
                                            </div>
                                        @elseif($latestApp)
                                            <span class="text-xs text-gray-400">Submitted</span>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Last Activity --}}
                                    <td class="px-4 py-3">
                                        <div class="text-xs font-medium {{ $isInactive ? 'text-purple-600' : 'text-gray-600' }}">
                                            {{ $lastActivity->format('d M Y') }}
                                        </div>
                                        <div class="text-xs {{ $isInactive ? 'text-purple-500 font-semibold' : 'text-gray-400' }}">
                                            {{ $activityLabel }}
                                        </div>
                                        @if($isInactive)
                                            <div class="text-xs text-purple-500 mt-0.5">⚡ Needs follow-up</div>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3">
                                        @if($isDeleted)
                                            <form method="POST" action="{{ route('admin.applicants.restore', $user->id) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition"
                                                        style="background:#16a34a;">
                                                    Restore
                                                </button>
                                            </form>
                                        @elseif($latestApp && !$isDraft)
                                            <a href="{{ route('admin.applications.show', $latestApp->id) }}"
                                               class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition"
                                               style="background:#011C3E;">
                                                View
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                            </a>
                                        @elseif($isDraft)
                                            <span class="text-xs text-gray-400 italic">In progress</span>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-gray-400 text-sm">No applicants found.</p>
                                        <a href="{{ route('admin.applicants.index') }}" class="text-xs text-blue-500 hover:underline mt-1 block">Clear all filters</a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
