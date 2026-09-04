@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">
                    <div class="max-w-xl">

                        {{-- Breadcrumb --}}
                        <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
                            <a href="{{ route('admin.portal.subjects.index') }}" class="hover:text-blue-600 transition">Subjects</a>
                            @if(isset($preProgram) && $preProgram)
                                <span>›</span>
                                <a href="{{ route('admin.portal.subjects.index',['semester_id'=>$semesterId,'program_id'=>$preProgram->id]) }}"
                                   class="hover:text-blue-600 transition">{{ $preProgram->name }}</a>
                            @endif
                            <span>›</span>
                            <span class="text-gray-600 font-medium">{{ isset($subject) ? 'Edit Subject' : 'New Subject' }}</span>
                        </div>

                        <h1 class="text-2xl font-bold text-blue-900 mb-6">
                            {{ isset($subject) ? 'Edit: ' . $subject->name : 'New Subject' }}
                        </h1>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ isset($subject) ? route('admin.portal.subjects.update',$subject) : route('admin.portal.subjects.store') }}"
                              class="space-y-5">
                            @csrf
                            @if(isset($subject)) @method('PUT') @endif

                            {{-- ── SECTION 1: Identity ──────────────────── --}}
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Basic Information</p>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Program *</label>
                                    <select name="program_id" id="programSelect"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition"
                                            required onchange="onProgramChange(this)">
                                        <option value="">Select program...</option>
                                        @foreach($programs->groupBy('degree_level') as $level => $progs)
                                            <optgroup label="{{ ucfirst($level) }}">
                                                @foreach($progs as $prog)
                                                    <option value="{{ $prog->id }}"
                                                        @selected(old('program_id', isset($subject) ? $subject->program_id : ($preProgram?->id ?? '')) == $prog->id)>
                                                        {{ $prog->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-5 gap-3">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Code *
                                            <button type="button" onclick="regenerateCode()"
                                                    class="text-xs text-blue-400 hover:text-blue-600 ml-1 font-normal" title="Regenerate">↻</button>
                                        </label>
                                        <input type="text" name="code" id="codeInput"
                                               value="{{ old('code', $subject->code ?? '') }}"
                                               placeholder="CE306" required
                                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                        <p class="text-xs text-gray-400 mt-1 truncate" id="codeHint"></p>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject Name *</label>
                                        <input type="text" name="name"
                                               value="{{ old('name', $subject->name ?? '') }}"
                                               placeholder="e.g. Structural Mechanics" required
                                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                    </div>
                                </div>
                            </div>

                            {{-- ── SECTION 2: Classification ────────────── --}}
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Classification & Scheduling</p>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type *</label>
                                        <select name="subject_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition" required>
                                            @foreach(\App\Models\Subject::TYPES as $value => $label)
                                                <option value="{{ $value }}" @selected(old('subject_type', $subject->subject_type ?? 'major_core') === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slots / Week *</label>
                                        <select name="hours_per_week" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition" required>
                                            <option value="1" @selected(old('hours_per_week', $subject->hours_per_week ?? 1) == 1)>1 slot &nbsp;— general</option>
                                            <option value="2" @selected(old('hours_per_week', $subject->hours_per_week ?? 1) == 2)>2 slots — core</option>
                                            <option value="3" @selected(old('hours_per_week', $subject->hours_per_week ?? 1) == 3)>3 slots — major</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Year *</label>
                                        <select name="year_of_study" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition" required>
                                            @foreach([1=>'Year 1',2=>'Year 2',3=>'Year 3',4=>'Year 4',5=>'Year 5'] as $val => $lbl)
                                                <option value="{{ $val }}" @selected(old('year_of_study', $subject->year_of_study ?? 1) == $val)>{{ $lbl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Credits *</label>
                                        <input type="number" name="credits"
                                               value="{{ old('credits', $subject->credits ?? 3) }}"
                                               min="1" max="10" required
                                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Semester *</label>
                                        <select name="semester_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition" required>
                                            @foreach($semesters as $sem)
                                                <option value="{{ $sem->id }}"
                                                    @selected(old('semester_id', $subject->semester_id ?? $semesterId ?? '') == $sem->id)>
                                                    {{ $sem->name }}{{ $sem->is_current ? ' (Current)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-gray-400 font-normal text-xs ml-1">optional</span></label>
                                    <textarea name="description" rows="2"
                                              placeholder="Brief description of what this subject covers..."
                                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition resize-none">{{ old('description', $subject->description ?? '') }}</textarea>
                                </div>
                            </div>

                            {{-- ── SECTION 3: First Schedule Slot (create only) ── --}}
                            @if(!isset($subject))
                                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-200 p-6 space-y-4">
                                    <div class="flex items-center gap-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">First Schedule Slot</p>
                                        <span class="text-xs text-gray-400 font-normal">(optional — can add later in Schedule Builder)</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="col-span-2">
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Default Lecturer</label>
                                            <select name="lecturer_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                                <option value="">— Skip for now —</option>
                                                @foreach($lecturers as $lec)
                                                    <option value="{{ $lec->id }}">{{ $lec->fullTitle() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Day</label>
                                            <select name="day" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)
                                                    <option>{{ $d }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Class Hour</label>
                                            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition"
                                                    onchange="document.getElementById('cs_ts').value=this.options[this.selectedIndex].dataset.start;
                                                          document.getElementById('cs_te').value=this.options[this.selectedIndex].dataset.end;">
                                                <option data-start="09:30" data-end="10:50">1st Hour — 09:30</option>
                                                <option data-start="11:00" data-end="12:20">2nd Hour — 11:00</option>
                                                <option data-start="12:50" data-end="14:10">3rd Hour — 12:50</option>
                                                <option data-start="14:20" data-end="15:40">4th Hour — 14:20</option>
                                            </select>
                                            <input type="hidden" name="time_start" id="cs_ts" value="09:30">
                                            <input type="hidden" name="time_end"   id="cs_te" value="10:50">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Room</label>
                                            <input type="text" name="room" placeholder="e.g. 5505"
                                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Building</label>
                                            <input type="text" name="building" placeholder="Building 5"
                                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Type</label>
                                            <select name="type" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition">
                                                <option value="lecture">Lecture</option>
                                                <option value="lab">Lab</option>
                                                <option value="seminar">Seminar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ── Actions ──────────────────────────────── --}}
                            <div class="flex items-center gap-3">
                                <button type="submit"
                                        class="px-8 py-3 rounded-xl text-sm font-bold text-white transition hover:shadow-md hover:-translate-y-px"
                                        style="background: #011C3E;">
                                    {{ isset($subject) ? 'Save Changes' : 'Create Subject' }}
                                </button>
                                <a href="{{ route('admin.portal.subjects.index',['semester_id'=>$semesterId??'','program_id'=>isset($preProgram)?$preProgram->id:(isset($subject)?$subject->program_id:'')]) }}"
                                   class="px-6 py-3 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const EXISTING_CODES   = {!! json_encode(isset($existingCodes)   ? $existingCodes   : []) !!};
        const PROGRAM_PREFIXES = {!! json_encode(isset($programPrefixes) ? $programPrefixes->toArray() : []) !!};

        function onProgramChange(select) {
            if (select.value) suggestCode(select.value);
        }

        function suggestCode(programId) {
            const prefix   = PROGRAM_PREFIXES[programId] || 'SUB';
            const existing = EXISTING_CODES[programId]   || [];
            const nums     = existing
                .filter(c => c.startsWith(prefix))
                .map(c => parseInt(c.replace(prefix, '')) || 0)
                .filter(n => n > 0);

            const next      = nums.length ? Math.max(...nums) + 1 : 301;
            const suggested = prefix + next;

            const input = document.getElementById('codeInput');
            if (!input.dataset.manuallyEdited) input.value = suggested;

            const hint = document.getElementById('codeHint');
            if (hint) hint.textContent = existing.length
                ? 'Used: ' + existing.slice(-4).join(', ') + (existing.length > 4 ? '…' : '')
                : 'First subject for this program';
        }

        function regenerateCode() {
            const pid = document.getElementById('programSelect').value;
            if (!pid) return;
            document.getElementById('codeInput').dataset.manuallyEdited = '';
            suggestCode(pid);
        }

        document.getElementById('codeInput')?.addEventListener('input', function() {
            this.dataset.manuallyEdited = 'yes';
        });

        document.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('programSelect');
            if (sel?.value) suggestCode(sel.value);
        });
    </script>
@endsection
