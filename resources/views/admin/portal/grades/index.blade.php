@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-blue-900">Grades Management</h1>
                        <p class="text-gray-500 text-sm mt-0.5">Select a program to view and enter grades</p>
                    </div>

                    {{-- ── STEP 1: Semester + Year selector ── --}}
                    <x-portal-filter-bar
                        route="{{ route('admin.portal.grades.index') }}"
                        :semesters="$semesters"
                        :semester-id="$semesterId"
                        :program-id="$programId"
                    />

                    {{-- ── STEP 2: Program selector grouped by degree level ── --}}
                    @php
                        $degreeOrder  = ['bachelor', 'master', 'phd'];
                        $degreeLabels = ['bachelor' => 'Bachelor Programs', 'master' => 'Master Programs', 'phd' => 'PhD Programs'];
                        $degreeColors = [
                            'bachelor' => ['#eff6ff', '#1d4ed8', '#bfdbfe'],
                            'master'   => ['#f5f3ff', '#7c3aed', '#ddd6fe'],
                            'phd'      => ['#fef3c7', '#b45309', '#fde68a'],
                        ];
                    @endphp

                    {{-- Program pills (same style as Schedule) --}}
                    <div class="mb-6">
                        @foreach($degreeOrder as $level)
                            @if(isset($programs[$level]))
                                @php [$bg, $color, $border] = $degreeColors[$level]; @endphp
                                <div class="mb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-bold uppercase tracking-widest" style="color:{{ $color }};">{{ $degreeLabels[$level] }}</span>
                                        <div class="flex-1 h-px" style="background:{{ $border }};"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($programs[$level] as $program)
                                            @php $isSel = $programId == $program->id; @endphp
                                            <a href="{{ route('admin.portal.grades.index', ['semester_id' => $semesterId, 'program_id' => $program->id, 'year_of_study' => request('year_of_study')]) }}"
                                               class="px-4 py-2 rounded-xl text-sm font-semibold border-2 transition-all"
                                               style="background:{{ $isSel?$color:$bg }};border-color:{{ $isSel?$color:$border }};color:{{ $isSel?'white':$color }};">
                                                {{ $program->name }}
                                                <span class="opacity-70 text-xs ml-1">({{ $program->subjectCount }})</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- ── STEP 3: Subjects for selected program ──────────── --}}
                    @if($selectedProgram)
                        @php
                            [$bg, $color, $border] = $degreeColors[$selectedProgram->degree_level] ?? ['#eff6ff','#1d4ed8','#bfdbfe'];
                        @endphp

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                            {{-- Section header --}}
                            <div class="px-6 py-4 border-b flex items-center justify-between"
                                 style="background: {{ $bg }}; border-color: {{ $border }};">
                                <div>
                                    <h2 class="font-bold" style="color: {{ $color }};">{{ $selectedProgram->name }}</h2>
                                    <p class="text-xs mt-0.5" style="color: {{ $color }}; opacity: 0.7;">
                                        {{ $currentSemester?->name }} &nbsp;·&nbsp; {{ $subjects->count() }} subjects
                                    </p>
                                </div>
                                @php
                                    $totalEnrolled = $subjects->sum('enrollments_count');
                                    $totalGraded   = $subjects->sum(fn($s) => $s->enrollments->filter(fn($e) => $e->grade)->count());
                                    $overallPct    = $totalEnrolled ? round(($totalGraded / $totalEnrolled) * 100) : 0;
                                @endphp
                                <div class="text-right">
                                    <p class="text-xs" style="color: {{ $color }}; opacity: 0.7;">Overall progress</p>
                                    <p class="text-lg font-bold" style="color: {{ $color }};">{{ $overallPct }}%</p>
                                    <p class="text-xs" style="color: {{ $color }}; opacity: 0.6;">{{ $totalGraded }}/{{ $totalEnrolled }} graded</p>
                                </div>
                            </div>

                            {{-- Subject rows --}}
                            @forelse($subjects as $subject)
                                @php
                                    $graded  = $subject->enrollments->filter(fn($e) => $e->grade)->count();
                                    $total   = $subject->enrollments_count;
                                    $pct     = $total ? round(($graded / $total) * 100) : 0;
                                    $avgGpa  = $subject->enrollments->filter(fn($e) => $e->grade?->gpa_points)->avg(fn($e) => $e->grade->gpa_points);
                                    $allDone = $graded === $total && $total > 0;
                                @endphp
                                <a href="{{ route('admin.portal.grades.subject', $subject) }}"
                                   class="flex items-center gap-5 px-6 py-4 border-b border-gray-50 hover:bg-gray-50 transition group">

                                    {{-- Code --}}
                                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-lg shrink-0"
                                          style="background: {{ $bg }}; color: {{ $color }};">{{ $subject->code }}</span>

                                    {{-- Name + progress --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-gray-800 text-sm group-hover:text-blue-900 transition">
                                                {{ $subject->name }}
                                            </p>
                                            @if($subject->year_of_study)
                                                <span class="text-xs px-1.5 py-0.5 rounded font-semibold" style="background:#dbeafe;color:#1e40af;">Y{{ $subject->year_of_study }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <div class="flex-1 max-w-32 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                                <div class="h-full rounded-full transition-all"
                                                     style="width: {{ $pct }}%; background: {{ $allDone ? '#22c55e' : $color }};"></div>
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $graded }}/{{ $total }} graded</span>
                                        </div>
                                    </div>

                                    {{-- Credits --}}
                                    <div class="shrink-0 text-center hidden md:block">
                                        <p class="text-xs text-gray-400">Credits</p>
                                        <p class="text-sm font-bold text-gray-700">{{ $subject->credits }}</p>
                                    </div>

                                    {{-- Avg GPA --}}
                                    <div class="shrink-0 text-center w-20">
                                        @if($avgGpa)
                                            <p class="text-xs text-gray-400">Avg GPA</p>
                                            <p class="text-sm font-bold" style="color: {{ $color }};">{{ number_format($avgGpa, 2) }}</p>
                                        @else
                                            <p class="text-xs text-gray-400">No grades</p>
                                        @endif
                                    </div>

                                    {{-- Status badge --}}
                                    <div class="shrink-0 w-24 text-right">
                                        @if($allDone)
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                            ✓ Complete
                                        </span>
                                        @elseif($graded > 0)
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                            In Progress
                                        </span>
                                        @else
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                                            Not Started
                                        </span>
                                        @endif
                                    </div>

                                    {{-- Arrow --}}
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @empty
                                <div class="px-6 py-10 text-center text-gray-400">No subjects found for this program.</div>
                            @endforelse
                        </div>

                    @elseif(!$programId)
                        {{-- Prompt to select a program --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #eff6ff;">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #011C3E;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-700 mb-1">Select a program above</p>
                            <p class="text-sm text-gray-400">Choose a degree level and program to view its subjects and enter grades</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
