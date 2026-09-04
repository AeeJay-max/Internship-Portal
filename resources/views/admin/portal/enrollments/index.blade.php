@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-blue-900">Enrollments</h1>
                        <p class="text-gray-500 text-sm mt-0.5">Students are automatically enrolled in all subjects of their program when approved. Use this page to verify and fix any missing enrollments.</p>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                        </div>
                    @endif

                    {{-- How it works info box --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <strong>MOSRAC School System:</strong> When an application is approved, the student is automatically enrolled in all subjects of their program for the current semester.
                            If subjects were added to a program after a student was approved, use <strong>Sync</strong> to catch up.
                        </div>
                    </div>

                    {{-- Semester + Year selector --}}
                    <x-portal-filter-bar
                        route="{{ route('admin.portal.enrollments.index') }}"
                        :semesters="$semesters"
                        :semester-id="$semesterId"
                        year-field="year_filter"
                        year-label="Filter by Year"
                        :program-id="$programId"
                    />

                    {{-- Program selector --}}
                    @php
                        $degreeOrder  = ['bachelor','master','phd'];
                        $degreeLabels = ['bachelor'=>'Bachelor Programs','master'=>'Master Programs','phd'=>'PhD Programs'];
                        $degreeColors = [
                            'bachelor' => ['#eff6ff','#1d4ed8','#bfdbfe'],
                            'master'   => ['#f5f3ff','#7c3aed','#ddd6fe'],
                            'phd'      => ['#fef3c7','#b45309','#fde68a'],
                        ];
                    @endphp

                    <div class="mb-6">
                        @foreach($degreeOrder as $level)
                            @if(isset($programs[$level]))
                                @php [$bg,$color,$border] = $degreeColors[$level]; @endphp
                                <div class="mb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-bold uppercase tracking-widest" style="color:{{ $color }};">{{ $degreeLabels[$level] }}</span>
                                        <div class="flex-1 h-px" style="background:{{ $border }};"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($programs[$level] as $program)
                                            @php $isSel = $programId == $program->id; @endphp
                                            <a href="{{ route('admin.portal.enrollments.index',['semester_id'=>$semesterId,'program_id'=>$program->id,'year_filter'=>request('year_filter')]) }}"
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

                    @if($selectedProgram)
                        @php
                            [$bg,$color,$border] = $degreeColors[$selectedProgram->degree_level] ?? ['#eff6ff','#1d4ed8','#bfdbfe'];
                            $yearFilter = request('year_filter');
                            $filteredStudents = $yearFilter
                                ? $programStudents->filter(fn($s) => ($s->current_year ?? 1) == $yearFilter)
                                : $programStudents;
                        @endphp

                        {{-- Program header + Sync All button --}}
                        <div class="rounded-2xl px-6 py-4 mb-4 flex items-center justify-between"
                             style="background:{{ $bg }};border:1px solid {{ $border }};">
                            <div>
                                <h2 class="font-bold" style="color:{{ $color }};">{{ $selectedProgram->name }}</h2>
                                <p class="text-xs mt-0.5" style="color:{{ $color }};opacity:.7;">
                                    {{ $currentSemester?->name }}
                                    &nbsp;·&nbsp; {{ $subjects->count() }} subjects
                                    &nbsp;·&nbsp; {{ $filteredStudents->count() }} students
                                    @if($yearFilter) &nbsp;·&nbsp; Year {{ $yearFilter }} only @endif
                                </p>
                            </div>
                            <form method="POST" action="{{ route('admin.portal.enrollments.syncProgram') }}">
                                @csrf
                                <input type="hidden" name="program_id"  value="{{ $programId }}">
                                <input type="hidden" name="semester_id" value="{{ $semesterId }}">
                                <button type="submit"
                                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white transition hover:opacity-90"
                                        style="background:{{ $color }};"
                                        onclick="return confirm('Sync all students in {{ $selectedProgram->name }}?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Sync All Students
                                </button>
                            </form>
                        </div>

                        {{-- Subjects this semester --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-3 border-b border-gray-100" style="background:#f8fafc;">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Program Subjects This Semester</p>
                            </div>
                            <div class="px-6 py-4 flex flex-wrap gap-2">
                                @forelse($subjects as $subject)
                                    @php [$stbg,$stc] = $subject->typeColors(); @endphp
                                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border"
                                          style="background:{{ $stbg }};color:{{ $stc }};border-color:{{ $stc }}22;">
                                        <span class="font-mono">{{ $subject->code }}</span>
                                        {{ $subject->name }}
                                        @if($subject->year_of_study)
                                            <span class="opacity-60">Y{{ $subject->year_of_study }}</span>
                                        @endif
                                    </span>
                                @empty
                                    <p class="text-sm text-gray-400">No subjects configured for this semester yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Students list --}}
                        @if($filteredStudents->isEmpty())
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                                <p class="font-semibold text-gray-600">No students found{{ $yearFilter ? ' in Year '.$yearFilter : '' }}</p>
                                <p class="text-sm text-gray-400 mt-1">{{ $yearFilter ? 'Try selecting a different year filter' : 'Students are added when their application is approved' }}</p>
                            </div>
                        @else
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="px-6 py-3 border-b border-gray-100 flex items-center justify-between" style="background:#f8fafc;">
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Enrolled Students</p>
                                    <p class="text-xs text-gray-400">{{ $filteredStudents->count() }} students · {{ $subjects->count() }} subjects each</p>
                                </div>

                                <div class="divide-y divide-gray-50">
                                    @foreach($filteredStudents as $student)
                                        @php
                                            $allEnrolled = $student->missingSubjects->isEmpty();
                                            $missing     = $student->missingSubjects->count();
                                        @endphp
                                        <div class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0"
                                                     style="background:{{ $color }};">
                                                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-semibold text-gray-800">{{ $student->user->name }}</p>
                                                        <span class="text-xs px-1.5 py-0.5 rounded font-bold" style="background:#dbeafe;color:#1e40af;">Y{{ $student->current_year ?? 1 }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-3 mt-0.5">
                                                        <span class="font-mono text-xs text-gray-400">{{ $student->student_number }}</span>
                                                        <span class="text-xs text-gray-400">{{ $student->user->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-center shrink-0">
                                                    <p class="text-xs text-gray-400 mb-1">{{ $student->enrolledCount }}/{{ $subjects->count() }} subjects</p>
                                                    <div class="flex gap-1">
                                                        @foreach($subjects as $subj)
                                                            @php $enrolled = in_array($subj->id, $student->enrolledSubjectIds); @endphp
                                                            <div class="w-4 h-2 rounded-full"
                                                                 style="background:{{ $enrolled ? $color : '#e5e7eb' }};"
                                                                 title="{{ $subj->code }}: {{ $enrolled ? 'Enrolled' : 'Missing' }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="shrink-0 w-28 text-right">
                                                    @if($allEnrolled)
                                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">✓ Fully enrolled</span>
                                                    @else
                                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700">{{ $missing }} missing</span>
                                                    @endif
                                                </div>
                                                @if(!$allEnrolled)
                                                    <form method="POST" action="{{ route('admin.portal.enrollments.store') }}" class="shrink-0">
                                                        @csrf
                                                        <input type="hidden" name="student_id"  value="{{ $student->id }}">
                                                        <input type="hidden" name="semester_id" value="{{ $semesterId }}">
                                                        <button type="submit"
                                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                                                                style="background:{{ $bg }};color:{{ $color }};border:1px solid {{ $border }};">
                                                            Sync
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            @if(!$allEnrolled)
                                                <div class="mt-3 pl-14">
                                                    <p class="text-xs text-orange-600 font-medium mb-1">Missing from:</p>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($student->missingSubjects as $subj)
                                                            <span class="text-xs px-2 py-0.5 rounded-lg font-mono font-bold bg-orange-50 text-orange-700 border border-orange-200">{{ $subj->code }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @elseif(!$programId)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#eff6ff;">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#011C3E;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-700 mb-1">Select a program above</p>
                            <p class="text-sm text-gray-400">View enrollment status for all students in that program</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
