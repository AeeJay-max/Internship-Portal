@extends('portal.layouts.app')

@section('content')

    @php
        $classHours = [
            '1' => ['label' => '1st Hour', 'start' => '09:30', 'end' => '10:50'],
            '2' => ['label' => '2nd Hour', 'start' => '11:00', 'end' => '12:20'],
            '3' => ['label' => '3rd Hour', 'start' => '12:50', 'end' => '14:10'],
            '4' => ['label' => '4th Hour', 'start' => '14:20', 'end' => '15:40'],
        ];
        $typeColors = [
            'lecture' => ['#dbeafe', '#1d4ed8'],
            'lab'     => ['#ede9fe', '#7c3aed'],
            'seminar' => ['#fef9c3', '#a16207'],
        ];
        // Build calendar: day → hour → slot
        $calendarData = [];
        foreach ($days as $day) {
            $calendarData[$day] = ['1'=>null,'2'=>null,'3'=>null,'4'=>null];
            foreach ($schedule[$day] as $slot) {
                $start = substr($slot['time_start'], 0, 5);
                foreach ($classHours as $num => $h) {
                    if ($start === $h['start']) {
                        $calendarData[$day][$num] = $slot;
                        break;
                    }
                }
            }
        }
        $totalClasses = collect($schedule)->flatten(1)->count();
    @endphp

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold" style="color: #011C3E;">My Schedule</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ $semester?->name }} &nbsp;·&nbsp; {{ $totalClasses }} classes/week</p>
        </div>
        <div class="text-xs font-bold px-3 py-1.5 rounded-xl" style="background:#eff6ff;color:#011C3E;">
            {{ now()->format('l') }}
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         MOBILE: Day-by-day list (default)
         DESKTOP: Weekly grid (md+)
    ═══════════════════════════════════════ --}}

    {{-- ── MOBILE VIEW ─────────────────────────── --}}
    <div class="md:hidden space-y-3">

        {{-- MOSRAC hours quick reference --}}
        <div class="card p-3 mb-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Class Hours</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach($classHours as $h)
                    <div class="flex items-center gap-2 text-xs">
                        <span class="font-bold w-16" style="color:#011C3E;">{{ $h['start'] }}</span>
                        <span class="text-gray-400">– {{ $h['end'] }}</span>
                        <span class="text-gray-300 ml-auto">{{ $h['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @foreach($days as $day)
            @php
                $daySlots = $schedule[$day];
                $isToday  = $day === $today;
            @endphp

            <div class="card overflow-hidden {{ $isToday ? 'ring-2 ring-blue-400' : '' }}">
                {{-- Day header --}}
                <div class="px-4 py-3 flex items-center gap-3 border-b border-gray-100"
                     style="{{ $isToday ? 'background: linear-gradient(135deg, #eff6ff, #dbeafe);' : 'background: #f8fafc;' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs"
                         style="{{ $isToday ? 'background:#011C3E;color:white;' : 'background:#e2e8f0;color:#64748b;' }}">
                        {{ substr($day, 0, 2) }}
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <span class="font-bold text-sm {{ $isToday ? 'text-blue-900' : 'text-gray-700' }}">{{ $day }}</span>
                        @if($isToday)
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white" style="background:#011C3E;">Today</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ $daySlots->count() }} class{{ $daySlots->count() !== 1 ? 'es' : '' }}</span>
                </div>

                @if($daySlots->isEmpty())
                    <div class="px-4 py-4 text-sm text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        No classes
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($daySlots as $slot)
                            @php [$tbg,$tc] = $typeColors[$slot['type']] ?? ['#f3f4f6','#6b7280']; @endphp
                            <div class="px-4 py-4 flex items-start gap-4">
                                {{-- Time --}}
                                <div class="shrink-0 text-center w-12">
                                    <p class="text-sm font-bold" style="color:#011C3E;">{{ substr($slot['time_start'],0,5) }}</p>
                                    <p class="text-xs text-gray-400">{{ substr($slot['time_end'],0,5) }}</p>
                                </div>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ $slot['subject'] }}</p>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full shrink-0"
                                              style="background:{{ $tbg }};color:{{ $tc }};">{{ ucfirst($slot['type']) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $slot['lecturer'] }}</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-lg"
                                          style="background:#f0f4f8;color:#011C3E;">{{ $slot['room'] }}</span>
                                        <span class="text-xs text-gray-400">{{ $slot['building'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ── DESKTOP WEEKLY CALENDAR GRID ────────── --}}
    <div class="hidden md:block">

        {{-- MOSRAC hours reference --}}
        <div class="card p-4 mb-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">MOSRAC Class Hours</p>
            <div class="grid grid-cols-4 gap-3">
                @foreach($classHours as $h)
                    <div class="text-center p-2.5 rounded-xl" style="background:#f0f4f8;">
                        <p class="text-xs font-bold text-gray-600">{{ $h['label'] }}</p>
                        <p class="text-sm font-bold mt-0.5" style="color:#011C3E;">{{ $h['start'] }}</p>
                        <p class="text-xs text-gray-400">– {{ $h['end'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4 mb-4">
            @foreach(['lecture'=>['#dbeafe','#1d4ed8'],'lab'=>['#ede9fe','#7c3aed'],'seminar'=>['#fef9c3','#a16207']] as $type=>[$tbg,$tc])
                <span class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                      style="background:{{ $tbg }};color:{{ $tc }};">
                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $tc }};"></span>
                {{ ucfirst($type) }}
            </span>
            @endforeach
        </div>

        {{-- Calendar grid --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" style="min-width:700px;">
                    <thead>
                    <tr>
                        <th class="w-24 px-3 py-3 text-xs font-bold text-gray-400 uppercase border-b border-r border-gray-100 text-center"
                            style="background:#f8fafc;">Hour</th>
                        @foreach($days as $day)
                            @php $isToday = $day === $today; @endphp
                            <th class="px-2 py-3 text-center text-xs font-bold uppercase border-b border-r border-gray-100 last:border-r-0"
                                style="background:{{ $isToday ? '#eff6ff' : '#f8fafc' }};color:{{ $isToday ? '#011C3E' : '#9ca3af' }};min-width:120px;">
                                {{ substr($day,0,3) }}
                                @if($isToday)<span class="block text-xs font-bold" style="color:#011C3E;">Today</span>@endif
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classHours as $hourNum => $hour)
                        <tr class="border-b border-gray-100 last:border-b-0">
                            {{-- Hour label --}}
                            <td class="px-3 py-3 border-r border-gray-100 text-center" style="background:#f8fafc;vertical-align:top;">
                                <p class="text-xs font-bold text-gray-600">{{ $hour['label'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $hour['start'] }}</p>
                                <p class="text-xs text-gray-300">{{ $hour['end'] }}</p>
                            </td>
                            {{-- Day cells --}}
                            @foreach($days as $day)
                                @php
                                    $cell    = $calendarData[$day][$hourNum] ?? null;
                                    $isToday = $day === $today;
                                @endphp
                                <td class="px-1.5 py-1.5 border-r border-gray-100 last:border-r-0"
                                    style="height:90px;vertical-align:top;background:{{ $isToday && !$cell ? '#fafbff' : 'white' }};">
                                    @if($cell)
                                        @php [$tbg,$tc] = $typeColors[$cell['type']] ?? ['#f3f4f6','#6b7280']; @endphp
                                        <div class="rounded-xl p-2 h-full"
                                             style="background:{{ $tbg }};border:1.5px solid {{ $tc }}33;">
                                            <p class="text-xs font-bold leading-none" style="color:{{ $tc }};">{{ $cell['code'] }}</p>
                                            <p class="text-xs font-semibold text-gray-700 mt-0.5 leading-tight">{{ Str::limit($cell['subject'], 20) }}</p>
                                            <p class="text-xs text-gray-400 mt-1 truncate leading-none">{{ explode(' ', $cell['lecturer'])[1] ?? $cell['lecturer'] }}</p>
                                            <div class="flex items-center gap-1 mt-1.5">
                                                <span class="font-mono text-xs font-bold px-1.5 py-0.5 rounded"
                                                      style="background:white;color:#011C3E;font-size:10px;">{{ $cell['room'] }}</span>
                                                <span class="text-xs px-1.5 py-0.5 rounded font-medium"
                                                      style="background:{{ $tc }}22;color:{{ $tc }};font-size:10px;">{{ ucfirst($cell['type']) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-xl h-full flex items-center justify-center"
                                             style="min-height:78px;background:#f9fafb;">
                                            <span class="text-gray-200 text-lg">—</span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
