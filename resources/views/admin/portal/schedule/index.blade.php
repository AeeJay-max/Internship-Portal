@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">

                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">Schedule Builder</h1>
                            <p class="text-gray-500 text-sm mt-0.5">Drag subjects from the panel into the timetable</p>
                        </div>
                        @if(session('success'))
                            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">{{ session('success') }}</div>
                        @endif
                        @if($errors->has('conflict'))
                            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">{{ $errors->first('conflict') }}</div>
                        @endif
                    </div>

                    {{-- Semester + Year selector --}}
                    <x-portal-filter-bar
                        route="{{ route('admin.portal.schedule.index') }}"
                        :semesters="$semesters"
                        :semester-id="$semesterId"
                        :program-id="$programId"
                    />

                    @php
                        $degreeOrder  = ['bachelor','master','phd'];
                        $degreeLabels = ['bachelor'=>'Bachelor','master'=>'Master','phd'=>'PhD'];
                        $degreeColors = [
                            'bachelor' => ['#eff6ff','#1d4ed8','#bfdbfe'],
                            'master'   => ['#f5f3ff','#7c3aed','#ddd6fe'],
                            'phd'      => ['#fef3c7','#b45309','#fde68a'],
                        ];
                        $classHours = [
                            '1' => ['label'=>'1st Hour','start'=>'09:30','end'=>'10:50'],
                            '2' => ['label'=>'2nd Hour','start'=>'11:00','end'=>'12:20'],
                            '3' => ['label'=>'3rd Hour','start'=>'12:50','end'=>'14:10'],
                            '4' => ['label'=>'4th Hour','start'=>'14:20','end'=>'15:40'],
                        ];
                        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        $typeColors = [
                            'lecture' => ['#dbeafe','#1d4ed8'],
                            'lab'     => ['#ede9fe','#7c3aed'],
                            'seminar' => ['#fef9c3','#a16207'],
                        ];
                    @endphp

                    {{-- Program pills --}}
                    <div class="mb-4">
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
                                            <a href="{{ route('admin.portal.schedule.index',['semester_id'=>$semesterId,'program_id'=>$program->id,'year_of_study'=>request('year_of_study')]) }}"
                                               class="px-4 py-2 rounded-xl text-sm font-semibold border-2 transition-all"
                                               style="background:{{ $isSel?$color:$bg }};border-color:{{ $isSel?$color:$border }};color:{{ $isSel?'white':$color }};">
                                                {{ $program->name }}
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

                            // Build slot lookup: "Day-HourNum" => slot data
                            $slotMap = [];
                            foreach ($subjects as $subject) {
                                foreach ($subject->scheduleSlots as $slot) {
                                    foreach ($classHours as $num => $h) {
                                        if ($slot->time_start_short === $h['start']) {
                                            $slotMap[$slot->day . '-' . $num] = [
                                                'slot'    => $slot,
                                                'subject' => $subject,
                                            ];
                                            break;
                                        }
                                    }
                                }
                            }

                            // Subjects needing more slots
                            $needingSlots = $subjects->filter(fn($s) => !$s->isFullyScheduled());
                        @endphp

                        {{-- ════════════════════════════════════════════════
                             MAIN LAYOUT: Calendar left, Subject panel right
                        ════════════════════════════════════════════════ --}}
                        <div class="flex gap-4 items-start">

                            {{-- ── TIMETABLE (left, takes most space) ─────── --}}
                            <div class="flex-1 min-w-0">

                                {{-- MOSRAC hours mini reference --}}
                                <div class="grid grid-cols-4 gap-2 mb-3">
                                    @foreach($classHours as $num => $h)
                                        <div class="text-center py-1.5 rounded-lg text-xs" style="background:#f0f4f8;">
                                            <span class="font-bold text-gray-600">{{ $h['label'] }}</span>
                                            <span class="text-gray-400 ml-1">{{ $h['start'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between" style="background:{{ $bg }};">
                                        <h2 class="font-bold text-sm" style="color:{{ $color }};">{{ $selectedProgram->name }}</h2>
                                        <div class="flex items-center gap-4 text-xs">
                                            <span style="color:{{ $color }};opacity:.7;">{{ $currentSemester?->name }}</span>
                                            <span class="flex items-center gap-1" style="color:{{ $color }};opacity:.7;">
                                            <span class="w-3 h-3 rounded" style="background:{{ $color }};opacity:.4;"></span> Assigned
                                        </span>
                                            <span class="flex items-center gap-1 text-gray-400">
                                            <span class="w-3 h-3 rounded border-2 border-dashed border-gray-300"></span> Drop here
                                        </span>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full border-collapse" style="min-width:560px;">
                                            <thead>
                                            <tr>
                                                <th class="w-20 px-2 py-3 text-xs font-bold text-gray-400 uppercase border-b border-r border-gray-100 text-center" style="background:#f8fafc;"></th>
                                                @foreach($days as $day)
                                                    @php $isToday = now()->format('l') === $day; @endphp
                                                    <th class="px-1 py-3 text-center text-xs font-bold uppercase border-b border-r border-gray-100 last:border-r-0"
                                                        style="background:{{ $isToday ? $bg : '#f8fafc' }};color:{{ $isToday ? $color : '#9ca3af' }};min-width:110px;">
                                                        {{ substr($day,0,3) }}
                                                        @if($isToday)<span class="block text-xs font-bold" style="color:{{ $color }};">Today</span>@endif
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($classHours as $hourNum => $hour)
                                                <tr class="border-b border-gray-100 last:border-b-0">
                                                    <td class="px-2 py-2 border-r border-gray-100 text-center" style="background:#f8fafc;">
                                                        <p class="text-xs font-bold text-gray-600">{{ $hour['label'] }}</p>
                                                        <p class="text-xs text-gray-400">{{ $hour['start'] }}</p>
                                                    </td>
                                                    @foreach($days as $day)
                                                        @php
                                                            $key  = $day . '-' . $hourNum;
                                                            $cell = $slotMap[$key] ?? null;
                                                        @endphp
                                                        <td class="px-1 py-1 border-r border-gray-100 last:border-r-0"
                                                            style="height:85px;vertical-align:top;"
                                                            data-cell="{{ $key }}"
                                                            data-day="{{ $day }}"
                                                            data-hour="{{ $hourNum }}"
                                                            data-start="{{ $hour['start'] }}"
                                                            data-end="{{ $hour['end'] }}">

                                                            @if($cell)
                                                                @php
                                                                    $s    = $cell['slot'];
                                                                    $subj = $cell['subject'];
                                                                    [$tbg,$tc] = $typeColors[$s->type] ?? ['#f3f4f6','#6b7280'];
                                                                @endphp
                                                                {{-- Assigned slot — draggable --}}
                                                                <div class="slot-card rounded-xl p-2 h-full cursor-grab active:cursor-grabbing select-none relative group"
                                                                     style="background:{{ $tbg }};border:1.5px solid {{ $tc }}44;"
                                                                     draggable="true"
                                                                     data-slot-id="{{ $s->id }}"
                                                                     data-subject-id="{{ $subj->id }}"
                                                                     data-subject-code="{{ $subj->code }}"
                                                                     data-subject-name="{{ $subj->name }}"
                                                                     data-type="{{ $s->type }}"
                                                                     data-lecturer-id="{{ $s->lecturer_id }}"
                                                                     data-room="{{ $s->room }}"
                                                                     data-building="{{ $s->building }}"
                                                                     data-cell="{{ $key }}">
                                                                    <p class="text-xs font-bold leading-none" style="color:{{ $tc }};">{{ $subj->code }}</p>
                                                                    <p class="text-xs font-semibold text-gray-700 mt-0.5 leading-tight">{{ Str::limit($subj->name, 18) }}</p>
                                                                    <p class="text-xs text-gray-400 mt-1 leading-none truncate">{{ $s->lecturer->name }}</p>
                                                                    <div class="flex items-center gap-1 mt-1">
                                                                        <span class="font-mono text-xs font-bold px-1 py-0.5 rounded text-xs"
                                                                              style="background:white;color:#011C3E;font-size:10px;">{{ $s->room }}</span>
                                                                    </div>
                                                                    {{-- Edit button on hover --}}
                                                                    <button type="button"
                                                                            class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition w-5 h-5 rounded flex items-center justify-center text-xs"
                                                                            style="background:white;color:#374151;"
                                                                            onclick="event.stopPropagation(); openEditModal({{ $s->id }})">
                                                                        ✎
                                                                    </button>
                                                                </div>
                                                            @else
                                                                {{-- Empty drop zone --}}
                                                                <div class="drop-zone rounded-xl h-full flex items-center justify-center transition-all"
                                                                     style="min-height:75px;border:2px dashed #e5e7eb;"
                                                                     data-cell="{{ $key }}"
                                                                     data-day="{{ $day }}"
                                                                     data-hour="{{ $hourNum }}"
                                                                     data-start="{{ $hour['start'] }}"
                                                                     data-end="{{ $hour['end'] }}">
                                                                    @if($needingSlots->count())
                                                                        <span class="text-gray-200 text-xl font-light drop-plus">+</span>
                                                                    @endif
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

                            {{-- ── SUBJECT PANEL (right, sticky) ──────────── --}}
                            <div class="w-52 shrink-0 sticky top-6 space-y-3">
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-100" style="background:{{ $bg }};">
                                        <p class="text-xs font-bold" style="color:{{ $color }};">Subjects Needing Slots</p>
                                        <p class="text-xs mt-0.5" style="color:{{ $color }};opacity:.7;">Drag into timetable</p>
                                    </div>

                                    @if($needingSlots->isEmpty())
                                        <div class="px-4 py-6 text-center">
                                            <div class="text-2xl mb-1">🎉</div>
                                            <p class="text-xs font-semibold text-green-700">All scheduled!</p>
                                        </div>
                                    @else
                                        <div class="p-2 space-y-1.5" id="subjectPanel">
                                            @foreach($needingSlots->sortBy('subject_type') as $subj)
                                                @php
                                                    $remaining = $subj->remainingSlots();
                                                    [$stbg,$stc] = $subj->typeColors();
                                                @endphp
                                                {{-- Panel card — draggable --}}
                                                <div class="panel-card rounded-xl p-2.5 cursor-grab active:cursor-grabbing select-none border"
                                                     style="background:{{ $stbg }};border-color:{{ $stc }}33;"
                                                     draggable="true"
                                                     data-subject-id="{{ $subj->id }}"
                                                     data-subject-code="{{ $subj->code }}"
                                                     data-subject-name="{{ $subj->name }}"
                                                     data-subject-type="{{ $subj->subject_type }}"
                                                     data-remaining="{{ $remaining }}">
                                                    <p class="text-xs font-bold" style="color:{{ $stc }};">{{ $subj->code }}</p>
                                                    <p class="text-xs font-semibold text-gray-700 leading-tight mt-0.5">{{ Str::limit($subj->name, 22) }}</p>
                                                    <div class="flex items-center justify-between mt-1.5">
                                                        <span class="text-xs text-gray-400">{{ $subj->typeLabel() }}</span>
                                                        <span class="text-xs font-bold px-1.5 py-0.5 rounded-full text-white" style="background:{{ $stc }};font-size:10px;">
                                                        {{ $remaining }} left
                                                    </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Slot status summary --}}
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">All Subjects</p>
                                    @foreach($subjects->sortBy('code') as $subj)
                                        @php
                                            $a = $subj->scheduleSlots->count();
                                            $n = $subj->hours_per_week;
                                            [$stbg,$stc] = $subj->typeColors();
                                        @endphp
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="text-xs font-mono font-bold w-12 shrink-0" style="color:{{ $color }};">{{ $subj->code }}</span>
                                            <div class="flex gap-0.5 flex-1">
                                                @for($i=1;$i<=$n;$i++)
                                                    <div class="h-2 flex-1 rounded-full" style="background:{{ $i<=$a?$color:'#e5e7eb' }};"></div>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-400 shrink-0">{{ $a }}/{{ $n }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    @elseif(!$programId)
                        <div class="bg-white rounded-2xl p-16 text-center border border-gray-100">
                            <p class="font-semibold text-gray-700 mb-1">Select a program above</p>
                            <p class="text-sm text-gray-400">to view and build its schedule</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         ADD SLOT MODAL (drop on empty cell)
    ══════════════════════════════════════════ --}}
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.55);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b flex items-center justify-between rounded-t-2xl" style="background:#011C3E;">
                <div>
                    <h3 class="font-bold text-white">Assign Class</h3>
                    <p class="text-sm text-white/70 mt-0.5" id="addModalLabel">—</p>
                </div>
                <button onclick="closeAddModal()" class="text-white/60 hover:text-white text-2xl leading-none">×</button>
            </div>
            <form method="POST" action="{{ route('admin.portal.schedule.store') }}" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="day"         id="addDay">
                <input type="hidden" name="time_start"  id="addStart">
                <input type="hidden" name="time_end"    id="addEnd">
                <input type="hidden" name="subject_id"  id="addSubjectId">

                {{-- Subject (pre-filled from drag, but changeable) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject</label>
                    <select name="subject_id" id="addSubjectSelect"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm" required
                            onchange="document.getElementById('addSubjectId').value=this.value">
                        <option value="">Select...</option>
                        @if($selectedProgram)
                            @foreach($subjects->groupBy('subject_type') as $type => $grp)
                                <optgroup label="{{ \App\Models\Subject::TYPES[$type] ?? $type }}">
                                    @foreach($grp as $subj)
                                        <option value="{{ $subj->id }}"
                                                data-remaining="{{ $subj->remainingSlots() }}"
                                            {{ $subj->isFullyScheduled() ? 'class=text-gray-300' : '' }}>
                                            {{ $subj->code }} — {{ $subj->name }}
                                            ({{ $subj->remainingSlots() }}/{{ $subj->hours_per_week }} slots)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lecturer</label>
                    <select name="lecturer_id" id="addLecturer"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm" required>
                        <option value="">Select...</option>
                        @foreach($lecturers as $lec)
                            <option value="{{ $lec->id }}">{{ $lec->fullTitle() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Room</label>
                        <input type="text" name="room" id="addRoom" placeholder="5505"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Building</label>
                        <input type="text" name="building" id="addBuilding" placeholder="Building 5"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                            <option value="lecture">Lecture</option>
                            <option value="lab">Lab</option>
                            <option value="seminar">Seminar</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-900 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-800 transition">
                        Assign
                    </button>
                    <button type="button" onclick="closeAddModal()"
                            class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT / MOVE SLOT MODAL --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.55);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b flex items-center justify-between rounded-t-2xl" style="background:#374151;">
                <div>
                    <h3 class="font-bold text-white" id="editModalTitle">Edit Slot</h3>
                    <p class="text-sm text-white/70 mt-0.5" id="editModalLabel">—</p>
                </div>
                <button onclick="closeEditModal()" class="text-white/60 hover:text-white text-2xl leading-none">×</button>
            </div>
            <form id="editForm" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="time_start" id="editStart">
                <input type="hidden" name="time_end"   id="editEnd">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Day</label>
                        <select name="day" id="editDay" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm" required>
                            @foreach($days as $d)<option>{{ $d }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Class Hour</label>
                        <select class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm"
                                onchange="setEditHour(this)">
                            @foreach($classHours as $n => $h)
                                <option value="{{ $n }}" data-start="{{ $h['start'] }}" data-end="{{ $h['end'] }}">
                                    {{ $h['label'] }} — {{ $h['start'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lecturer</label>
                    <select name="lecturer_id" id="editLecturer" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm" required>
                        @foreach($lecturers as $lec)
                            <option value="{{ $lec->id }}">{{ $lec->fullTitle() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Room</label>
                        <input type="text" name="room" id="editRoom" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm" placeholder="5505">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Building</label>
                        <input type="text" name="building" id="editBuilding" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type</label>
                        <select name="type" id="editType" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                            <option value="lecture">Lecture</option>
                            <option value="lab">Lab</option>
                            <option value="seminar">Seminar</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-gray-800 text-white py-2.5 rounded-xl font-semibold hover:bg-gray-700 transition">
                        Save Changes
                    </button>
                    <button type="button" id="deleteBtn"
                            class="px-4 py-2.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition font-semibold">
                        Remove
                    </button>
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                </div>
            </form>
            <form id="deleteForm" method="POST" class="hidden">@csrf @method('DELETE')</form>
        </div>
    </div>

    {{-- MOVE CONFIRMATION (drop on filled cell) --}}
    <div id="swapModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.55);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
            <h3 class="font-bold text-gray-800 mb-2">Swap Slots?</h3>
            <p class="text-sm text-gray-500 mb-1" id="swapInfo"></p>
            <p class="text-xs text-orange-600 mb-5 font-medium">⚠ This cell is already occupied. Swapping will move the existing slot.</p>
            <div class="flex gap-3">
                <button onclick="confirmSwap()" class="flex-1 bg-orange-600 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-700 transition">Swap</button>
                <button onclick="closeSwapModal()" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">Cancel</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const CLASS_HOURS = {
                '1':{ start:'09:30', end:'10:50', label:'1st Hour' },
                '2':{ start:'11:00', end:'12:20', label:'2nd Hour' },
                '3':{ start:'12:50', end:'14:10', label:'3rd Hour' },
                '4':{ start:'14:20', end:'15:40', label:'4th Hour' },
            };

            // Build slot data from PHP
            const SLOTS = {!! json_encode(
    isset($subjects) ? $subjects->flatMap(fn($s) => $s->scheduleSlots->map(fn($slot) => [
        'id'         => $slot->id,
        'day'        => $slot->day,
        'time_start' => $slot->time_start_short,
        'lecturer_id'=> $slot->lecturer_id,
        'room'       => $slot->room,
        'building'   => $slot->building,
        'type'       => $slot->type,
        'update_url' => route('admin.portal.schedule.update', $slot),
        'delete_url' => route('admin.portal.schedule.destroy', $slot),
    ]))->keyBy('id')->toArray() : []
) !!};

            let dragData = null; // what's being dragged
            let pendingSwap = null; // swap state

            // ── DRAG FROM PANEL CARD ──────────────────────────────────────
            document.querySelectorAll('.panel-card').forEach(card => {
                card.addEventListener('dragstart', e => {
                    dragData = {
                        source: 'panel',
                        subjectId:   card.dataset.subjectId,
                        subjectCode: card.dataset.subjectCode,
                        subjectName: card.dataset.subjectName,
                    };
                    card.style.opacity = '0.5';
                    e.dataTransfer.effectAllowed = 'copy';
                });
                card.addEventListener('dragend', e => { card.style.opacity = '1'; });
            });

            // ── DRAG FROM TIMETABLE SLOT ──────────────────────────────────
            document.querySelectorAll('.slot-card').forEach(card => {
                card.addEventListener('dragstart', e => {
                    dragData = {
                        source:      'slot',
                        slotId:      card.dataset.slotId,
                        subjectId:   card.dataset.subjectId,
                        subjectCode: card.dataset.subjectCode,
                        subjectName: card.dataset.subjectName,
                        fromCell:    card.dataset.cell,
                    };
                    card.style.opacity = '0.4';
                    e.dataTransfer.effectAllowed = 'move';
                });
                card.addEventListener('dragend', e => { card.style.opacity = '1'; dragData = null; });
            });

            // ── DROP ZONES (empty cells) ──────────────────────────────────
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.addEventListener('dragover', e => {
                    e.preventDefault();
                    zone.style.borderColor = '#3b82f6';
                    zone.style.background  = '#eff6ff';
                });
                zone.addEventListener('dragleave', e => {
                    zone.style.borderColor = '#e5e7eb';
                    zone.style.background  = '';
                });
                zone.addEventListener('drop', e => {
                    e.preventDefault();
                    zone.style.borderColor = '#e5e7eb';
                    zone.style.background  = '';
                    if (!dragData) return;

                    const day   = zone.dataset.day;
                    const hour  = zone.dataset.hour;
                    const start = zone.dataset.start;
                    const end   = zone.dataset.end;

                    if (dragData.source === 'panel') {
                        // New slot from panel
                        openAddModal(day, hour, start, end, dragData.subjectId, dragData.subjectCode, dragData.subjectName);
                    } else if (dragData.source === 'slot') {
                        // Move existing slot
                        moveSlot(dragData.slotId, day, start, end);
                    }
                    dragData = null;
                });
            });

            // ── FILLED CELLS (allow drop for swap) ───────────────────────
            document.querySelectorAll('.slot-card').forEach(card => {
                card.addEventListener('dragover', e => {
                    if (dragData && dragData.source === 'slot' && dragData.fromCell !== card.dataset.cell) {
                        e.preventDefault();
                        card.style.outline = '2px solid #f97316';
                    }
                });
                card.addEventListener('dragleave', e => { card.style.outline = ''; });
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.style.outline = '';
                    if (!dragData || dragData.source !== 'slot') return;
                    if (dragData.fromCell === card.dataset.cell) return;

                    // Propose swap
                    const targetSlotId = card.dataset.slotId;
                    const targetCell   = card.closest('td');
                    pendingSwap = {
                        movingSlotId: dragData.slotId,
                        targetSlotId: targetSlotId,
                        newDay:   targetCell.dataset.day,
                        newStart: targetCell.dataset.start,
                        newEnd:   targetCell.dataset.end,
                        oldDay:   dragData.fromCell.split('-')[0],
                        oldStart: CLASS_HOURS[dragData.fromCell.split('-')[1]]?.start,
                        oldEnd:   CLASS_HOURS[dragData.fromCell.split('-')[1]]?.end,
                    };
                    document.getElementById('swapInfo').textContent =
                        'Move ' + dragData.subjectCode + ' to ' + pendingSwap.newDay + ' ' + pendingSwap.newStart +
                        ', and move ' + card.dataset.subjectCode + ' to ' + (pendingSwap.oldDay || '?') + ' ' + (pendingSwap.oldStart || '?');
                    document.getElementById('swapModal').classList.replace('hidden','flex');
                    dragData = null;
                });
            });

            function closeSwapModal() {
                pendingSwap = null;
                document.getElementById('swapModal').classList.replace('flex','hidden');
            }

            function confirmSwap() {
                if (!pendingSwap) return;
                // Move slot A to new position, then move slot B to old position
                // We do two sequential form POSTs via fetch
                const p = pendingSwap;
                closeSwapModal();

                Promise.all([
                    submitMove(p.movingSlotId, p.newDay, p.newStart, p.newEnd),
                    submitMove(p.targetSlotId, p.oldDay, p.oldStart, p.oldEnd),
                ]).then(() => window.location.reload());
            }

            function submitMove(slotId, day, start, end) {
                const slot = SLOTS[slotId];
                if (!slot) return Promise.resolve();
                const form = new FormData();
                form.append('_method', 'PUT');
                form.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                form.append('day',        day);
                form.append('time_start', start);
                form.append('time_end',   end);
                form.append('lecturer_id',slot.lecturer_id);
                form.append('room',       slot.room || '');
                form.append('building',   slot.building || '');
                form.append('type',       slot.type);
                return fetch(slot.update_url, { method:'POST', body:form });
            }

            function moveSlot(slotId, day, start, end) {
                const slot = SLOTS[slotId];
                if (!slot) return;
                const form = new FormData();
                form.append('_method', 'PUT');
                form.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                form.append('day',        day);
                form.append('time_start', start);
                form.append('time_end',   end);
                form.append('lecturer_id',slot.lecturer_id);
                form.append('room',       slot.room || '');
                form.append('building',   slot.building || '');
                form.append('type',       slot.type);
                fetch(slot.update_url, { method:'POST', body:form })
                    .then(() => window.location.reload());
            }

            // ── ADD MODAL ─────────────────────────────────────────────────
            function openAddModal(day, hour, start, end, subjectId, subjectCode, subjectName) {
                document.getElementById('addDay').value    = day;
                document.getElementById('addStart').value  = start;
                document.getElementById('addEnd').value    = end;
                document.getElementById('addSubjectId').value = subjectId || '';
                document.getElementById('addModalLabel').textContent =
                    day + ' · ' + CLASS_HOURS[hour]?.label + ' (' + start + ')';

                // Pre-select subject if dragged from panel
                const sel = document.getElementById('addSubjectSelect');
                for (let opt of sel.options) {
                    opt.selected = (subjectId && opt.value == subjectId);
                }
                document.getElementById('addModal').classList.replace('hidden','flex');
            }
            function closeAddModal() { document.getElementById('addModal').classList.replace('flex','hidden'); }

            // Click empty cell to open add modal
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.addEventListener('click', e => {
                    if (dragData) return;
                    const day   = zone.dataset.day;
                    const hour  = zone.dataset.hour;
                    const start = zone.dataset.start;
                    const end   = zone.dataset.end;
                    openAddModal(day, hour, start, end, null, null, null);
                });
            });

            // ── EDIT MODAL ────────────────────────────────────────────────
            function openEditModal(slotId) {
                const slot = SLOTS[slotId];
                if (!slot) return;

                document.getElementById('editForm').action = slot.update_url;
                document.getElementById('editModalLabel').textContent = slot.day + ' · ' + slot.time_start;
                document.getElementById('editStart').value    = slot.time_start;
                document.getElementById('editRoom').value     = slot.room || '';
                document.getElementById('editBuilding').value = slot.building || '';

                // Set day
                const daySel = document.getElementById('editDay');
                for (let opt of daySel.options) { opt.selected = opt.value === slot.day; }

                // Set hour
                const hourSel = document.querySelector('#editModal select[onchange="setEditHour(this)"]');
                for (let opt of hourSel.options) {
                    if (opt.dataset.start === slot.time_start) {
                        opt.selected = true;
                        document.getElementById('editStart').value = opt.dataset.start;
                        document.getElementById('editEnd').value   = opt.dataset.end;
                        break;
                    }
                }

                // Set lecturer
                const lecSel = document.getElementById('editLecturer');
                for (let opt of lecSel.options) { opt.selected = opt.value == slot.lecturer_id; }

                // Set type
                const typeSel = document.getElementById('editType');
                for (let opt of typeSel.options) { opt.selected = opt.value === slot.type; }

                // Delete button
                document.getElementById('deleteBtn').onclick = function() {
                    if (!confirm('Remove this slot?')) return;
                    const df = document.getElementById('deleteForm');
                    df.action = slot.delete_url;
                    df.submit();
                };

                document.getElementById('editModal').classList.replace('hidden','flex');
            }
            function closeEditModal() { document.getElementById('editModal').classList.replace('flex','hidden'); }
            function setEditHour(select) {
                const opt = select.options[select.selectedIndex];
                document.getElementById('editStart').value = opt.dataset.start;
                document.getElementById('editEnd').value   = opt.dataset.end;
            }

            // Close on backdrop click
            ['addModal','editModal','swapModal'].forEach(id => {
                document.getElementById(id).addEventListener('click', function(e) {
                    if (e.target === this) this.classList.replace('flex','hidden');
                });
            });
        </script>
    @endpush
@endsection
