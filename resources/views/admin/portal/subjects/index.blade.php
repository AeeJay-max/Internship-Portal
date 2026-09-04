@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Subjects</h1>
                            <p class="text-gray-500 text-sm mt-0.5">Select a program to view and manage its curriculum</p>
                        </div>
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.portal.subjects.create') }}"
                               class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition hover:opacity-90" style="background:#1e40af;">
                                + New Subject
                            </a>
                        @endif
                    </div>
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
                    @endif
                    {{-- Semester + Year selector --}}
                    <x-portal-filter-bar
                        route="{{ route('admin.portal.subjects.index') }}"
                        :semesters="$semesters"
                        :semester-id="$semesterId"
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
                                            <a href="{{ route('admin.portal.subjects.index',['semester_id'=>$semesterId,'program_id'=>$program->id,'year_of_study'=>request('year_of_study')]) }}"
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
                    {{-- Subject list for selected program --}}
                    @if($selectedProgram)
                        @php [$bg,$color,$border] = $degreeColors[$selectedProgram->degree_level] ?? ['#eff6ff','#1d4ed8','#bfdbfe']; @endphp
                        <div class="rounded-2xl px-6 py-4 mb-4 flex items-center justify-between"
                             style="background:{{ $bg }};border:1px solid {{ $border }};">
                            <div>
                                <h2 class="font-bold" style="color:{{ $color }};">{{ $selectedProgram->name }}</h2>
                                <p class="text-xs mt-0.5" style="color:{{ $color }};opacity:.7;">
                                    {{ $currentSemester?->name }} &nbsp;·&nbsp; {{ $subjects->count() }} subjects
                                    @if(request('year_of_study')) &nbsp;·&nbsp; Year {{ request('year_of_study') }} only @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-4 text-xs" style="color:{{ $color }};opacity:.7;">
                                @php
                                    $scheduled = $subjects->filter(fn($s) => $s->isFullyScheduled())->count();
                                    $total     = $subjects->count();
                                @endphp
                                <span>{{ $scheduled }}/{{ $total }} fully scheduled</span>
                                @if(Auth::user()->isSuperAdmin())
                                    <a href="{{ route('admin.portal.subjects.create',['program_id'=>$programId,'semester_id'=>$semesterId]) }}"
                                       class="font-bold px-3 py-1 rounded-lg text-white transition"
                                       style="background:{{ $color }};">+ Add Subject</a>
                                @endif
                            </div>
                        </div>
                        @foreach($subjects->groupBy('year_of_study') as $year => $yearSubjects)
                            <div class="mb-6">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                                         style="background:{{ $color }};">{{ $year }}</div>
                                    <h3 class="font-bold text-gray-700">Year {{ $year }}</h3>
                                    <div class="flex-1 h-px bg-gray-200"></div>
                                    <span class="text-xs text-gray-400">{{ $yearSubjects->count() }} subjects</span>
                                </div>
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="divide-y divide-gray-50">
                                        @foreach($yearSubjects->sortBy('subject_type') as $subject)
                                            @php
                                                $assigned  = $subject->scheduleSlots->count();
                                                $needed    = $subject->hours_per_week;
                                                $schedOk   = $assigned >= $needed;
                                                [$stbg,$stc] = $subject->typeColors();
                                            @endphp
                                            <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition">
                                                <span class="font-mono text-xs font-bold px-2.5 py-1.5 rounded-lg shrink-0"
                                                      style="background:{{ $bg }};color:{{ $color }};">{{ $subject->code }}</span>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <p class="font-bold text-gray-800">{{ $subject->name }}</p>
                                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full shrink-0"
                                                              style="background:{{ $stbg }};color:{{ $stc }};">{{ $subject->typeLabel() }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-4 mt-1 text-xs text-gray-400">
                                                        <span>{{ $subject->credits }} credits</span>
                                                        <span>{{ $subject->hoursLabel() }}</span>
                                                        <span>{{ $subject->enrollments_count }} enrolled</span>
                                                    </div>
                                                </div>
                                                <div class="shrink-0 text-center w-28">
                                                    <div class="flex gap-1 justify-center mb-1">
                                                        @for($i=1;$i<=$needed;$i++)
                                                            <div class="w-5 h-2 rounded-full" style="background:{{ $i<=$assigned?$color:'#e5e7eb' }};"></div>
                                                        @endfor
                                                    </div>
                                                    @if($schedOk)
                                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">✓ Scheduled</span>
                                                    @else
                                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700">
                                                            {{ $needed-$assigned }} slot{{ $needed-$assigned>1?'s':'' }} needed
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="shrink-0 w-40 hidden lg:block">
                                                    @forelse($subject->scheduleSlots as $slot)
                                                        <p class="text-xs text-gray-500 leading-relaxed">
                                                            <span class="font-semibold text-gray-700">{{ substr($slot->day,0,3) }}</span>
                                                            {{ $slot->time_start_short }}
                                                            <span class="font-mono text-gray-400">{{ $slot->room }}</span>
                                                        </p>
                                                    @empty
                                                        <p class="text-xs text-gray-300 italic">No slots yet</p>
                                                    @endforelse
                                                </div>
                                                <div class="shrink-0 flex items-center gap-3">
                                                    <a href="{{ route('admin.portal.subjects.show',$subject) }}"
                                                       class="text-sm font-semibold hover:underline" style="color:{{ $color }};">
                                                        Manage →
                                                    </a>
                                                    @if(Auth::user()->isSuperAdmin())
                                                        <a href="{{ route('admin.portal.subjects.edit',$subject) }}"
                                                           class="text-sm text-gray-400 hover:text-gray-600">Edit</a>
                                                        <form method="POST" action="{{ route('admin.portal.subjects.destroy',$subject) }}"
                                                              onsubmit="return confirm('Delete {{ $subject->name }}?')">
                                                            @csrf @method('DELETE')
                                                            <button class="text-sm text-red-400 hover:text-red-600">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @elseif(!$programId)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#eff6ff;">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#011C3E;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-700 mb-1">Select a program above</p>
                            <p class="text-sm text-gray-400">Choose a degree level and program to manage its subjects</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
