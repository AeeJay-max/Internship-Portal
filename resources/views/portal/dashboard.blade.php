@extends('portal.layouts.app')

@section('content')

    @php
        $program     = $student->application?->program;
        $degreeLabel = match($program?->degree_level) { 'bachelor' => 'BSc', 'master' => 'MSc', 'phd' => 'PhD', default => '' };
        $degreeColor = match($program?->degree_level) { 'bachelor' => '#1d4ed8', 'master' => '#7c3aed', 'phd' => '#b45309', default => '#011C3E' };
        $degreeBg    = match($program?->degree_level) { 'bachelor' => '#eff6ff', 'master' => '#f5f3ff', 'phd' => '#fef3c7', default => '#eff6ff' };
        $sc = match(true) {
            str_contains($standing, 'Excellent')    => ['#dcfce7','#15803d','#22c55e'],
            str_contains($standing, 'Good')         => ['#dbeafe','#1d4ed8','#3b82f6'],
            str_contains($standing, 'Satisfactory') => ['#fef9c3','#a16207','#eab308'],
            default                                 => ['#fee2e2','#dc2626','#ef4444'],
        };
        $gpaPercent = $semesterGpa ? min(100, round(($semesterGpa / 4.0) * 100)) : 0;
    @endphp

    {{-- ── IDENTITY CARD ─────────────────────────────────────── --}}
    <div class="portal-gradient rounded-2xl p-5 md:p-8 mb-5 shadow-xl relative overflow-hidden">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10" style="background:white;"></div>
        <div class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full opacity-5" style="background:white;"></div>

        <div class="relative flex items-center gap-4">
            {{-- Avatar --}}
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-2xl flex items-center justify-center text-xl md:text-3xl font-bold text-white shrink-0"
                 style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-lg md:text-2xl font-bold text-white leading-tight">{{ Auth::user()->name }}</h1>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full shrink-0"
                          style="background: {{ $degreeBg }}; color: {{ $degreeColor }};">{{ $degreeLabel }}</span>
                </div>
                <p class="text-sm mt-0.5 truncate" style="color: #90caf9;">{{ $program?->name ?? 'N/A' }}</p>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <span class="font-mono text-xs text-white/60">{{ $student->student_number }}</span>
                    <span class="flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-semibold"
                          style="background: {{ $sc[0] }}; color: {{ $sc[1] }};">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $sc[2] }};"></span>
                    {{ $standing }}
                </span>
                </div>
            </div>

            {{-- GPA Ring --}}
            <div class="relative w-16 h-16 md:w-24 md:h-24 shrink-0">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="38" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="10"/>
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#90caf9" stroke-width="10"
                            stroke-dasharray="239"
                            stroke-dashoffset="{{ 239 - (239 * $gpaPercent / 100) }}"
                            stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-base md:text-xl font-bold text-white leading-none">{{ $semesterGpa ? number_format($semesterGpa, 2) : 'N/A' }}</span>
                    <span class="text-xs mt-0.5" style="color: #90caf9; font-size: 9px;">GPA</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STATS ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        @foreach([
            ['Semester GPA',   $semesterGpa ? number_format($semesterGpa, 2) : 'N/A',  'Current', '#011C3E', '#eff6ff'],
            ['Cumul. GPA',     $cumulativeGpa ? number_format($cumulativeGpa, 2) : 'N/A', 'All time', '#7c3aed', '#f5f3ff'],
            ['Subjects',       $enrollments->count(), 'This semester', '#611818', '#e0f2fe'],
            ['Credits',        $totalCredits, 'Total enrolled', '#b45309', '#fef3c7'],
        ] as [$label, $value, $sub, $color, $bg])
            <div class="card p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ $label }}</p>
                <p class="text-2xl md:text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $value }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $sub }}</p>
            </div>
        @endforeach
    </div>

    {{-- ── TODAY'S SCHEDULE ────────────────────────────────────── --}}
    <div class="card mb-5">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-800 text-sm">Today's Classes</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l, F d') }}</p>
            </div>
            <a href="{{ route('portal.schedule') }}" class="text-xs font-semibold" style="color: #011C3E;">Full week →</a>
        </div>
        @if($todaySlots->isEmpty())
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: #f0f4f8;">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium text-sm">No classes today</p>
                <p class="text-xs text-gray-400 mt-1">Enjoy your day!</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($todaySlots as $slot)
                    @php
                        $tb = $slot['type'] === 'lab' ? ['#f5f3ff','#7c3aed'] : ($slot['type'] === 'seminar' ? ['#fef9c3','#a16207'] : ['#eff6ff','#1d4ed8']);
                    @endphp
                    <div class="px-5 py-4 flex items-start gap-4">
                        <div class="text-center shrink-0 w-12">
                            <p class="text-sm font-bold" style="color: #011C3E;">{{ substr($slot['time_start'], 0, 5) }}</p>
                            <p class="text-xs text-gray-400">{{ substr($slot['time_end'], 0, 5) }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $slot['subject'] }}</p>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full shrink-0"
                                      style="background: {{ $tb[0] }}; color: {{ $tb[1] }};">{{ ucfirst($slot['type']) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $slot['lecturer'] }}</p>
                            <div class="flex items-center gap-2 mt-1">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-lg"
                                  style="background: #f0f4f8; color: #011C3E;">{{ $slot['room'] }}</span>
                                <span class="text-xs text-gray-400">{{ $slot['building'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── SUBJECTS & GRADES ────────────────────────────────────── --}}
    <div class="card mb-5">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-800 text-sm">Subjects & Grades</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $semester?->name }}</p>
            </div>
            <a href="{{ route('portal.grades') }}" class="text-xs font-semibold" style="color: #7c3aed;">All grades →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($enrollments as $enrollment)
                @php
                    $letter = $enrollment->grade?->letter_grade;
                    $gc = match(true) {
                        $letter === 'A'                   => ['#dcfce7','#15803d'],
                        in_array($letter, ['B+','B'])      => ['#dbeafe','#1d4ed8'],
                        in_array($letter, ['C+','C'])      => ['#fef9c3','#a16207'],
                        $letter === 'D'                   => ['#fed7aa','#c2410c'],
                        $letter === 'F'                   => ['#fee2e2','#dc2626'],
                        default                           => ['#f3f4f6','#9ca3af'],
                    };
                @endphp
                <div class="px-5 py-3.5 flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $enrollment->subject->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $enrollment->subject->code }} · {{ $enrollment->subject->credits }} cr.
                            @if($enrollment->grade?->total) · {{ $enrollment->grade->total }}/100 @endif
                        </p>
                    </div>
                    @if($letter)
                        <div class="flex flex-col items-center shrink-0">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold"
                              style="background: {{ $gc[0] }}; color: {{ $gc[1] }};">{{ $letter }}</span>
                            <span class="text-xs text-gray-400 mt-0.5">{{ number_format($enrollment->grade->gpa_points, 1) }}</span>
                        </div>
                    @else
                        <span class="text-xs text-gray-400">In progress</span>
                    @endif
                </div>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">No subjects enrolled</div>
            @endforelse
        </div>
    </div>

    {{-- ── ANNOUNCEMENTS ──────────────────────────────────────── --}}
    <div class="card">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Announcements</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($announcements as $news)
                <div class="px-5 py-4">
                    <p class="text-sm font-semibold text-gray-800">{{ $news->title }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-xs text-gray-400">{{ $news->published_at?->format('M d, Y') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">{{ ucfirst($news->category) }}</span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-gray-400 text-sm">No announcements</div>
            @endforelse
        </div>
    </div>

@endsection
