@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    {{-- Top bar --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <a href="{{ route('admin.students.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Students</a>
                                <span class="text-gray-300">/</span>
                                <span class="text-sm text-gray-600 font-mono">{{ $student->student_number }}</span>
                            </div>
                            <h1 class="text-2xl font-bold text-blue-900">{{ $student->user?->name }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            @if(Auth::user()->isSuperAdmin())
                                <form method="POST" action="{{ route('admin.students.promoteYear', $student->id) }}"
                                      onsubmit="return confirm('Promote {{ $student->user?->name }} to Year {{ ($student->current_year ?? 1) + 1 }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-semibold text-white rounded-lg transition hover:shadow-md"
                                            style="background:#011C3E;">
                                        Promote to Year {{ ($student->current_year ?? 1) + 1 }} ↑
                                    </button>
                                </form>
                            @endif
                            @if($student->application_id)
                                <a href="{{ route('admin.applications.show', $student->application_id) }}"
                                   class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                                    View Application →
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
                    @endif

                    {{-- ═══ ROW 1: Profile card + Academic summary ═══ --}}
                    <div class="grid lg:grid-cols-3 gap-6 mb-6">

                        {{-- Profile card --}}
                        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">

                            {{-- Photo --}}
                            <div class="flex flex-col items-center mb-6">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-100 mb-3 relative group">
                                    @if($student->user?->profile_photo)
                                        <img src="{{ asset('storage/' . $student->user->profile_photo) }}"
                                             class="w-full h-full object-cover" id="student-photo">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white"
                                             style="background: linear-gradient(135deg, #011C3E, #611818);" id="student-photo-placeholder">
                                            {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <h2 class="font-bold text-gray-800 text-lg text-center">{{ $student->user?->name }}</h2>
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $student->student_number }}</p>

                                {{-- Photo upload (admin only) --}}
                                <form method="POST"
                                      action="{{ route('admin.students.updatePhoto', $student->id) }}"
                                      enctype="multipart/form-data"
                                      class="mt-3">
                                    @csrf
                                    @method('POST')
                                    <label class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 hover:border-gray-400 transition text-gray-500 hover:text-gray-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Change Photo
                                        <input type="file" name="photo" accept="image/*" class="hidden"
                                               onchange="this.closest('form').submit()">
                                    </label>
                                </form>
                            </div>

                            {{-- Key info --}}
                            <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Email</span>
                                    <span class="font-medium text-gray-700 text-xs">{{ $student->user?->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Enrolled</span>
                                    <span class="font-medium text-gray-700">{{ $student->enrollment_date?->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Year</span>
                                    <span class="font-bold text-blue-900">Year {{ $student->current_year ?? 1 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Type</span>
                                    <span class="font-medium text-gray-700">{{ $student->application?->is_transfer ? 'Transfer' : 'Regular' }}</span>
                                </div>
                                @if($student->application?->personalInfo?->phone)
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Phone</span>
                                        <span class="font-medium text-gray-700">{{ $student->application->personalInfo->phone }}</span>
                                    </div>
                                @endif
                                @if($student->application?->personalInfo?->nationality)
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Nationality</span>
                                        <span class="font-medium text-gray-700">{{ $student->application->personalInfo->nationality }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Academic summary --}}
                        <div class="lg:col-span-2 grid grid-cols-2 gap-4">

                            {{-- Program --}}
                            <div class="bg-white rounded-2xl shadow border border-gray-100 p-5 col-span-2">
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Program</p>
                                <p class="text-lg font-bold text-blue-900" style="font-family:'Georgia',serif;">
                                    {{ $student->application?->program?->name ?? '—' }}
                                </p>
                                <div class="flex items-center gap-3 mt-2">
                                    @php
                                        $level = $student->application?->program?->degree_level;
                                        $lc = ['bachelor'=>['BSc','#eff6ff','#1d4ed8'],'master'=>['MSc','#f5f3ff','#7c3aed'],'phd'=>['PhD','#fef3c7','#b45309']];
                                        $lb = $lc[$level] ?? ['—','#f1f5f9','#64748b'];
                                    @endphp
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                          style="background:{{ $lb[1] }};color:{{ $lb[2] }}">{{ $lb[0] }}</span>
                                    @if($student->application?->program?->faculty)
                                        <span class="text-xs text-gray-400">{{ $student->application->program->faculty }}</span>
                                    @endif
                                    @if($student->application?->admissionCycle?->intake_name)
                                        <span class="text-xs text-gray-400">· {{ $student->application->admissionCycle->intake_name }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Cumulative GPA --}}
                            @php $cgpa = $student->cumulativeGpa(); @endphp
                            <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Cumulative GPA</p>
                                <p class="text-3xl font-bold {{ $cgpa >= 3.5 ? 'text-green-600' : ($cgpa >= 2.0 ? 'text-blue-900' : 'text-red-600') }}">
                                    {{ $cgpa ? number_format($cgpa, 2) : 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $student->academicStanding() }}</p>
                            </div>

                            {{-- Total credits --}}
                            <div class="bg-white rounded-2xl shadow border border-gray-100 p-5">
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Total Credits</p>
                                <p class="text-3xl font-bold text-blue-900">{{ $student->totalCredits() }}</p>
                                <p class="text-xs text-gray-400 mt-1">Credits earned</p>
                            </div>

                        </div>
                    </div>

                    {{-- ═══ ROW 2: Enrollments + Grades ═══ --}}
                    @php
                        $allEnrollments = $student->enrollments()
                            ->with(['subject', 'semester', 'grade'])
                            ->orderBy('semester_id', 'desc')
                            ->get()
                            ->groupBy('semester_id');
                    @endphp

                    @if($allEnrollments->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow border border-gray-100 mb-6 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="font-bold text-blue-900">Subjects & Grades</h3>
                                <span class="text-xs text-gray-400">{{ $student->enrollments()->count() }} total enrollments</span>
                            </div>

                            @foreach($allEnrollments as $semesterId => $semEnrollments)
                                @php $semester = $semEnrollments->first()->semester; @endphp
                                <div class="border-b border-gray-100 last:border-0">
                                    {{-- Semester header --}}
                                    <div class="px-6 py-3 flex items-center gap-3" style="background:#f8fafc;">
                                        <span class="font-bold text-sm text-gray-700">{{ $semester?->name ?? 'Semester ' . $semesterId }}</span>
                                        @if($semester?->is_current)
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Current</span>
                                        @endif
                                        @php
                                            $semGpa = $student->semesterGpa($semesterId);
                                            $semCredits = $semEnrollments->sum(fn($e) => $e->subject->credits ?? 0);
                                        @endphp
                                        <span class="ml-auto text-xs text-gray-400">{{ $semCredits }} credits</span>
                                        @if($semGpa)
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">GPA {{ number_format($semGpa, 2) }}</span>
                                        @endif
                                    </div>

                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-y border-gray-100">
                                        <tr>
                                            <th class="px-6 py-2 text-left text-xs font-semibold text-gray-400 uppercase">Subject</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Credits</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Midterm</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Final</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Participation</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Total</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Grade</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">GPA</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                        @foreach($semEnrollments as $enrollment)
                                            @php $grade = $enrollment->grade; @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-3">
                                                    <div class="font-medium text-gray-800">{{ $enrollment->subject?->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ $enrollment->subject?->code }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $enrollment->subject?->credits }}</td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $grade?->midterm_score ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $grade?->final_score ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $grade?->participation_score ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center font-semibold text-gray-800">{{ $grade?->total_score ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($grade?->letter_grade)
                                                        @php
                                                            $lg = $grade->letter_grade;
                                                            $gc = match(true) {
                                                                in_array($lg, ['A', 'A+']) => ['bg-green-100','text-green-700'],
                                                                str_starts_with($lg, 'B')  => ['bg-blue-50','text-blue-700'],
                                                                str_starts_with($lg, 'C')  => ['bg-yellow-50','text-yellow-700'],
                                                                default                    => ['bg-red-50','text-red-600'],
                                                            };
                                                        @endphp
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $gc[0] }} {{ $gc[1] }}">{{ $lg }}</span>
                                                    @else
                                                        <span class="text-gray-300">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center font-semibold {{ $grade?->gpa_points >= 3.0 ? 'text-green-600' : ($grade?->gpa_points >= 2.0 ? 'text-gray-700' : 'text-red-500') }}">
                                                    {{ $grade?->gpa_points ?? '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow border border-gray-100 p-8 text-center mb-6">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-gray-400 text-sm">No subject enrollments yet.</p>
                        </div>
                    @endif

                    {{-- ═══ ROW 3: Application timeline + Personal info ═══ --}}
                    <div class="grid lg:grid-cols-2 gap-6">

                        {{-- Application Timeline --}}
                        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                            <h3 class="font-bold text-blue-900 mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Application Timeline
                            </h3>

                            @forelse($student->application?->logs ?? [] as $log)
                                <div class="flex gap-3 mb-4 last:mb-0">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2.5 h-2.5 rounded-full mt-1 shrink-0" style="background:#011C3E;"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 flex-1 mt-1" style="background:#e5e7eb;"></div>
                                        @endif
                                    </div>
                                    <div class="pb-4">
                                        <p class="text-sm font-medium text-gray-800">{{ $log->action }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $log->created_at->format('d M Y, H:i') }}
                                            @if($log->performer) — by <span class="font-medium">{{ $log->performer->name }}</span> @endif
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm">No events logged.</p>
                            @endforelse

                            {{-- Approved by / when summary --}}
                            @php
                                $approvalLog = $student->application?->logs
                                    ?->where('action', 'like', '%approved%')
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp
                            @if($approvalLog)
                                <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400">
                                    ✓ Approved on {{ $approvalLog->created_at->format('d M Y') }}
                                    @if($approvalLog->performer) by <strong>{{ $approvalLog->performer->name }}</strong> @endif
                                </div>
                            @endif
                        </div>

                        {{-- Personal Info --}}
                        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                            <h3 class="font-bold text-blue-900 mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Personal Information
                            </h3>

                            @if($student->application?->personalInfo)
                                @php $pi = $student->application->personalInfo; @endphp
                                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                    @foreach([
                                        ['Date of Birth', $pi->date_of_birth],
                                        ['Gender', $pi->gender ? ucfirst($pi->gender) : null],
                                        ['Nationality', $pi->nationality],
                                        ['Citizenship', $pi->citizenship],
                                        ['Passport', $pi->passport_number],
                                        ['Country of Birth', $pi->country_of_birth],
                                        ['Place of Birth', $pi->place_of_birth],
                                        ['Resident Country', $pi->resident_country],
                                        ['City', $pi->city],
                                        ['Address', $pi->address],
                                        ['Marital Status', $pi->marital_status ? ucfirst($pi->marital_status) : null],
                                        ['Armenian', $pi->armenian_proficiency],
                                    ] as [$label, $value])
                                        @if($value)
                                            <div>
                                                <p class="text-xs text-gray-400">{{ $label }}</p>
                                                <p class="font-medium text-gray-700 mt-0.5">{{ $value }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 text-sm">No personal information on record.</p>
                            @endif

                            {{-- Academic background summary --}}
                            @if($student->application?->academicInfo)
                                @php $ai = $student->application->academicInfo; @endphp
                                <div class="mt-5 pt-5 border-t border-gray-100">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Prior Education</p>
                                    <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                        @if($ai->school_name)
                                            <div>
                                                <p class="text-xs text-gray-400">School / Institution</p>
                                                <p class="font-medium text-gray-700 mt-0.5">{{ $ai->school_name }}</p>
                                            </div>
                                        @endif
                                        @if($ai->gpa)
                                            <div>
                                                <p class="text-xs text-gray-400">Prior GPA</p>
                                                <p class="font-medium text-gray-700 mt-0.5">{{ $ai->gpa }}</p>
                                            </div>
                                        @endif
                                        @if($ai->graduation_year)
                                            <div>
                                                <p class="text-xs text-gray-400">Graduated</p>
                                                <p class="font-medium text-gray-700 mt-0.5">{{ $ai->graduation_year }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
