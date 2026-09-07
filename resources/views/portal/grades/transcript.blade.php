@extends('portal.layouts.app')

@section('content')

    <div class="mb-6 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-bold" style="color: #011C3E; font-family: 'Georgia', serif;">Academic Transcript</h1>
            <p class="text-gray-500 text-sm mt-0.5">Official academic record</p>
        </div>
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold border-2 transition-all hover:shadow-md"
                style="border-color: #011C3E; color: #011C3E; background: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save PDF
        </button>
    </div>

    {{-- Transcript Document --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- University Header --}}
        <div class="px-10 py-8 text-center" style="background: linear-gradient(135deg, #011627, #011C3E);">
            <p class="text-white/60 text-xs tracking-widest uppercase mb-2">Republic of Armenia</p>
            <h2 class="text-white text-2xl font-bold" style="font-family: 'Georgia', serif;">
                National Internship Portal of Armenia
            </h2>
            <p class="text-white/60 text-sm mt-1">Gyumri Institute of Technology</p>
            <div class="w-20 h-0.5 mx-auto mt-4 mb-4" style="background: rgba(255,255,255,0.3);"></div>
            <p class="text-white text-lg font-semibold tracking-wider uppercase" style="letter-spacing: 0.15em;">
                Official Academic Transcript
            </p>
            <p class="text-white/50 text-xs mt-2">Generated: {{ now()->format('F d, Y \a\t H:i') }}</p>
        </div>

        {{-- Student Info Block --}}
        @php
            $student = Auth::user()->student;
            $cumulativeGpa = collect($semesterData)->avg('gpa');
        @endphp
        <div class="px-10 py-6 border-b border-gray-100">
            <div class="grid md:grid-cols-2 gap-x-12 gap-y-3 text-sm">
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Student Name</span>
                    <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Student ID</span>
                    <span class="font-mono font-bold text-gray-900">{{ $student->student_number }}</span>
                </div>
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Program</span>
                    <span class="font-semibold text-gray-800">{{ $student->program?->name }}</span>
                </div>
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Degree</span>
                    <span class="font-semibold text-gray-800">{{ ucfirst($student->program?->degree_level) }} of Science</span>
                </div>
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Enrollment Date</span>
                    <span class="font-semibold text-gray-800">{{ $student->enrollment_date?->format('F d, Y') }}</span>
                </div>
                <div class="flex gap-4 items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 w-36 shrink-0">Cumulative GPA</span>
                    <span class="font-bold text-xl" style="color: #011C3E;">
                        {{ $cumulativeGpa ? number_format($cumulativeGpa, 2) : 'N/A' }}
                        <span class="text-sm font-normal text-gray-400">/ 4.00</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Grading Scale Reference --}}
        <div class="px-10 py-4 border-b border-gray-100" style="background: #f8fafc;">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Grading Scale</p>
            <div class="flex flex-wrap gap-3">
                @foreach(['A' => ['90-100','4.00','#dcfce7','#15803d'], 'B+' => ['85-89','3.50','#dbeafe','#1d4ed8'], 'B' => ['80-84','3.00','#dbeafe','#1d4ed8'], 'C+' => ['75-79','2.50','#fef9c3','#a16207'], 'C' => ['70-74','2.00','#fef9c3','#a16207'], 'D' => ['60-69','1.00','#fed7aa','#c2410c'], 'F' => ['0-59','0.00','#fee2e2','#dc2626']] as $letter => [$range, $pts, $bg, $color])
                    <div class="flex items-center gap-1.5 text-xs px-2.5 py-1.5 rounded-lg border border-gray-100"
                         style="background: white;">
                        <span class="font-bold w-6 text-center px-1 py-0.5 rounded"
                              style="background: {{ $bg }}; color: {{ $color }};">{{ $letter }}</span>
                        <span class="text-gray-500">{{ $range }}</span>
                        <span class="text-gray-400">·</span>
                        <span class="font-mono text-gray-600">{{ $pts }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Academic Records --}}
        <div class="px-10 py-6">
            @forelse($semesterData as $data)
                <div class="mb-8">
                    {{-- Semester Header --}}
                    <div class="flex items-center gap-4 mb-3 pb-2 border-b-2" style="border-color: #011C3E;">
                        <h3 class="font-bold text-gray-900">{{ $data['semester']->name }}</h3>
                        <span class="text-sm text-gray-500">{{ $data['semester']->academic_year }}</span>
                        <span class="ml-auto font-semibold text-sm" style="color: #011C3E;">
                            GPA: {{ $data['gpa'] ? number_format($data['gpa'], 2) : 'N/A' }}
                        </span>
                    </div>

                    <table class="min-w-full text-sm">
                        <thead>
                        <tr style="background: #f8fafc;">
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Course</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Title</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Cr.</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Total</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Grade</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">GPA Pts</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">Quality Pts</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($data['enrollments'] as $enrollment)
                            @php
                                $g = $enrollment->grade;
                                $letter = $g?->letter_grade;
                                $qualityPts = $g && $g->gpa_points ? round($g->gpa_points * $enrollment->subject->credits, 2) : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-gray-600 text-xs">{{ $enrollment->subject->code }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $enrollment->subject->name }}</td>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $enrollment->subject->credits }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-800">{{ $g?->total ?? '—' }}</td>
                                <td class="px-4 py-3 text-center">
                                        <span class="font-bold {{ $letter === 'F' ? 'text-red-600' : ($letter ? 'text-gray-800' : 'text-gray-400') }}">
                                            {{ $letter ?? 'IP' }}
                                        </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $g?->gpa_points ? number_format($g->gpa_points, 2) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $qualityPts ? number_format($qualityPts, 2) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr style="background: #f0f4f8;">
                            <td colspan="2" class="px-4 py-2.5 text-xs font-bold text-gray-700 border-t border-gray-200">Semester Summary</td>
                            <td class="px-4 py-2.5 text-center text-xs font-bold text-gray-800 border-t border-gray-200">{{ $data['credits'] }}</td>
                            <td colspan="3" class="border-t border-gray-200"></td>
                            <td class="px-4 py-2.5 text-center text-xs font-bold border-t border-gray-200" style="color: #011C3E;">
                                {{ $data['gpa'] ? number_format($data['gpa'], 2) : '—' }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No academic records found.</p>
            @endforelse

            {{-- Cumulative Summary --}}
            @if(count($semesterData) > 0)
                <div class="mt-4 p-4 rounded-xl border-2 flex items-center justify-between" style="border-color: #011C3E; background: #eff6ff;">
                    <span class="font-bold text-gray-800">Cumulative Grade Point Average</span>
                    <span class="text-2xl font-bold" style="color: #011C3E;">
                        {{ $cumulativeGpa ? number_format($cumulativeGpa, 2) : 'N/A' }} / 4.00
                    </span>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-10 py-5 border-t border-gray-100 text-center" style="background: #f8fafc;">
            <p class="text-xs text-gray-400">
                This transcript is an unofficial document generated from the MoSRAC Student Portal for reference purposes only.
            </p>
            <p class="text-xs text-gray-400 mt-1">
                For official certified transcripts, please contact the Registrar's Office at the National Internship Portal of Armenia.
            </p>
        </div>
    </div>

@endsection
