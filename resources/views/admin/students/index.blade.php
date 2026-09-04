@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Students</h1>
                            <p class="text-gray-500 mt-1 text-sm">{{ $students->total() }} enrolled students</p>
                        </div>
                    </div>

                    {{-- ── Summary stat cards ── --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        {{-- Total --}}
                        <a href="{{ route('admin.students.index') }}"
                           class="bg-white rounded-xl border p-4 shadow-sm hover:shadow-md transition group
                              {{ !request()->hasAny(['program','level','year','search']) ? 'border-blue-900 ring-1 ring-blue-900' : 'border-gray-100' }}">
                            <p class="text-2xl font-bold text-blue-900">{{ $totalStudents }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">All Students</p>
                        </a>
                        {{-- By level --}}
                        @foreach(['bachelor' => ['BSc','#eff6ff','#1d4ed8'], 'master' => ['MSc','#f5f3ff','#7c3aed'], 'phd' => ['PhD','#fef3c7','#b45309']] as $lvl => [$badge, $bg, $color])
                            @php $count = isset($byLevel[$lvl]) ? $byLevel[$lvl]->count() : 0; @endphp
                            <a href="{{ route('admin.students.index', ['level' => $lvl]) }}"
                               class="bg-white rounded-xl border p-4 shadow-sm hover:shadow-md transition
                                  {{ request('level') === $lvl ? 'ring-1' : 'border-gray-100' }}"
                               style="{{ request('level') === $lvl ? 'border-color:'.$color.';ring-color:'.$color : '' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-2xl font-bold" style="color:#011C3E;">{{ $count }}</p>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                          style="background:{{ $bg }};color:{{ $color }}">{{ $badge }}</span>
                                </div>
                                <p class="text-xs text-gray-400">{{ ucfirst($lvl) }}</p>
                            </a>
                        @endforeach
                    </div>

                    {{-- ── Filters ── --}}
                    <form method="GET" action="{{ route('admin.students.index') }}"
                          class="bg-white rounded-xl shadow border border-gray-100 p-4 mb-6">

                        <div class="flex flex-wrap gap-3 items-end">

                            {{-- Search --}}
                            <div class="flex-1 min-w-48">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Search</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Name, email or student number…"
                                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                                </div>
                            </div>

                            {{-- Program --}}
                            <div class="min-w-44">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Program</label>
                                <select name="program"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                                    <option value="">All Programs</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ request('program') == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Degree level --}}
                            <div class="min-w-36">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Degree</label>
                                <select name="level"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                                    <option value="">All Levels</option>
                                    <option value="bachelor" {{ request('level') === 'bachelor' ? 'selected' : '' }}>Bachelor's</option>
                                    <option value="master"   {{ request('level') === 'master'   ? 'selected' : '' }}>Master's</option>
                                    <option value="phd"      {{ request('level') === 'phd'      ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>

                            {{-- Year of study --}}
                            <div class="min-w-32">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Year</label>
                                <select name="year"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                                    <option value="">All Years</option>
                                    @for($y = 1; $y <= $maxYear; $y++)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-2">
                                <button type="submit"
                                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition hover:shadow-md"
                                        style="background:#011C3E;">
                                    Filter
                                </button>
                                @if(request()->hasAny(['search','program','level','year']))
                                    <a href="{{ route('admin.students.index') }}"
                                       class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-500">
                                        Clear
                                    </a>
                                @endif
                            </div>

                        </div>

                        {{-- Active filter chips --}}
                        @if(request()->hasAny(['search','program','level','year']))
                            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-400 self-center">Active filters:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ route('admin.students.index', array_merge(request()->except('search', 'page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                                @if(request('program'))
                                    @php $pName = $programs->find(request('program'))?->name; @endphp
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-purple-50 text-purple-700">
                                    {{ $pName }}
                                    <a href="{{ route('admin.students.index', array_merge(request()->except('program', 'page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                                @if(request('level'))
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">
                                    {{ ucfirst(request('level')) }}
                                    <a href="{{ route('admin.students.index', array_merge(request()->except('level', 'page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                                @if(request('year'))
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-700">
                                    Year {{ request('year') }}
                                    <a href="{{ route('admin.students.index', array_merge(request()->except('year', 'page'))) }}" class="opacity-60 hover:opacity-100">✕</a>
                                </span>
                                @endif
                            </div>
                        @endif

                    </form>

                    {{-- ── Table ── --}}
                    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Student</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Student No.</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Program</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Year</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Enrolled</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50 transition">

                                        {{-- Student avatar + name --}}
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                                    @if($student->user?->profile_photo)
                                                        <img src="{{ asset('storage/' . $student->user->profile_photo) }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-xs font-bold text-white"
                                                             style="background:linear-gradient(135deg,#011C3E,#611818);">
                                                            {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-800 text-sm">{{ $student->user?->name }}</div>
                                                    <div class="text-gray-400 text-xs">{{ $student->user?->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Student number --}}
                                        <td class="px-4 py-3">
                                            <span class="font-mono text-sm font-semibold text-blue-900">{{ $student->student_number }}</span>
                                        </td>

                                        {{-- Program + degree badge --}}
                                        <td class="px-4 py-3">
                                            @php
                                                $level = $student->application?->program?->degree_level;
                                                $lc = ['bachelor'=>['BSc','#eff6ff','#1d4ed8'],'master'=>['MSc','#f5f3ff','#7c3aed'],'phd'=>['PhD','#fef3c7','#b45309']];
                                                $lb = $lc[$level] ?? null;
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                @if($lb)
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full shrink-0"
                                                          style="background:{{ $lb[1] }};color:{{ $lb[2] }}">{{ $lb[0] }}</span>
                                                @endif
                                                <span class="text-sm text-gray-700">{{ $student->application?->program?->name ?? '—' }}</span>
                                            </div>
                                        </td>

                                        {{-- Year --}}
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-semibold text-blue-900">Year {{ $student->current_year ?? 1 }}</span>
                                        </td>

                                        {{-- Enrollment date --}}
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $student->enrollment_date?->format('d M Y') }}
                                        </td>

                                        {{-- Action --}}
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                               class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition text-white"
                                               style="background:#011C3E;">
                                                View
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p class="text-gray-400 text-sm font-medium">No students match your filters.</p>
                                            <a href="{{ route('admin.students.index') }}" class="text-xs text-blue-500 hover:underline mt-1 block">Clear all filters</a>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($students->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100">
                                {{ $students->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
