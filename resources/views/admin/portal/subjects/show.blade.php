@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <a href="{{ route('admin.portal.subjects.index') }}" class="text-blue-600 hover:underline text-sm">← Subjects</a>
                            <h1 class="text-2xl font-bold text-blue-900 mt-1">{{ $subject->name }}</h1>
                            <p class="text-gray-500 text-sm mt-0.5">
                                <span class="font-mono font-bold">{{ $subject->code }}</span>
                                &nbsp;·&nbsp; {{ $subject->program?->name }}
                                &nbsp;·&nbsp; {{ $subject->semester?->name }}
                                &nbsp;·&nbsp; {{ $subject->credits }} credits
                            </p>
                        </div>
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.portal.subjects.edit', $subject) }}"
                               class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
                                Edit Subject
                            </a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
                    @endif

                    <div class="grid lg:grid-cols-2 gap-6">

                        {{-- Schedule Slots --}}
                        <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" style="background: #f8fafc;">
                                <h2 class="font-bold text-gray-800">Schedule Slots</h2>
                                <span class="text-xs text-gray-400">{{ $subject->scheduleSlots->count() }} slots</span>
                            </div>

                            {{-- Existing slots --}}
                            @forelse($subject->scheduleSlots as $slot)
                                <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $slot->day }} &nbsp;·&nbsp; {{ substr($slot->time_start, 0, 5) }}–{{ substr($slot->time_end, 0, 5) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $slot->lecturer->fullTitle() }} &nbsp;·&nbsp;
                                            <span class="font-mono">{{ $slot->room }}</span> ({{ $slot->building }}) &nbsp;·&nbsp;
                                            {{ ucfirst($slot->type) }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.portal.schedule.destroy', $slot) }}" onsubmit="return confirm('Remove this slot?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                    </form>
                                </div>
                            @empty
                                <div class="px-6 py-4 text-sm text-gray-400">No schedule slots yet.</div>
                            @endforelse

                            {{-- Add slot form --}}
                            <form method="POST" action="{{ route('admin.portal.schedule.store') }}" class="px-6 py-4 bg-gray-50">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Add Schedule Slot</p>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Lecturer</label>
                                        <select name="lecturer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                                            <option value="">Select...</option>
                                            @foreach($lecturers as $lec)
                                                <option value="{{ $lec->id }}">{{ $lec->fullTitle() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Day</label>
                                        <select name="day" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                                                <option>{{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Start Time</label>
                                        <input type="time" name="time_start" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">End Time</label>
                                        <input type="time" name="time_end" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Building</label>
                                        <input type="text" name="building" placeholder="e.g. Building 5" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Room</label>
                                        <input type="text" name="room" placeholder="e.g. 5505" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                        <option value="lecture">Lecture</option>
                                        <option value="lab">Lab</option>
                                        <option value="seminar">Seminar</option>
                                    </select>
                                    <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-800 transition">
                                        Add Slot
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Enrolled Students --}}
                        <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" style="background: #f8fafc;">
                                <h2 class="font-bold text-gray-800">Enrolled Students</h2>
                                <span class="text-xs text-gray-400">{{ $subject->enrollments->count() }} students</span>
                            </div>

                            {{-- Student list --}}
                            @forelse($subject->enrollments as $enrollment)
                                <div class="px-6 py-3.5 border-b border-gray-50 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $enrollment->student->user->name }}</p>
                                        <p class="text-xs font-mono text-gray-400">{{ $enrollment->student->student_number }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($enrollment->grade?->letter_grade)
                                            @php $letter = $enrollment->grade->letter_grade; $gc = match(true) { $letter==='A' => ['#dcfce7','#15803d'], in_array($letter,['B+','B']) => ['#dbeafe','#1d4ed8'], in_array($letter,['C+','C']) => ['#fef9c3','#a16207'], $letter==='D' => ['#fed7aa','#c2410c'], default => ['#fee2e2','#dc2626'] }; @endphp
                                            <span class="text-sm font-bold w-8 h-8 rounded-lg inline-flex items-center justify-center"
                                                  style="background: {{ $gc[0] }}; color: {{ $gc[1] }};">{{ $letter }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">No grade</span>
                                        @endif
                                        <form method="POST" action="{{ route('admin.portal.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Remove enrollment?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-600 text-xs">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-4 text-sm text-gray-400">No students enrolled.</div>
                            @endforelse

                            {{-- Quick Grade Link --}}
                            @if($subject->enrollments->isNotEmpty())
                                <div class="px-6 py-4 bg-gray-50">
                                    <a href="{{ route('admin.portal.grades.subject', $subject) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:shadow transition"
                                       style="background: #011C3E;">
                                        Enter / Edit Grades →
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
