@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-8xl mx-auto px-4 md:px-6">
        <div class="flex gap-6">
            @include('admin.partials.sidebar')
            <div class="flex-1 min-w-0">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <a href="{{ route('admin.portal.grades.index') }}" class="text-blue-600 hover:underline text-sm">← Grades</a>
                        </div>
                        <h1 class="text-2xl font-bold text-blue-900">{{ $subject->name }}</h1>
                        <p class="text-gray-500 text-sm mt-0.5">
                            <span class="font-mono">{{ $subject->code }}</span>
                            &nbsp;·&nbsp; {{ $subject->program?->name }}
                            &nbsp;·&nbsp; {{ $subject->semester?->name }}
                            &nbsp;·&nbsp; {{ $subject->credits }} credits
                        </p>
                    </div>
                </div>

                {{-- Grading Info --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-sm text-blue-700">
                    <strong>Grading System:</strong> Midterm 1 (max 20) + Midterm 2 (max 20) = Combined Midterm (max 40) &nbsp;·&nbsp; Final Exam (max 60) &nbsp;·&nbsp; Total = 100
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
                @endif

                {{-- Grades Table --}}
                <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" style="background: #f8fafc;">
                        <h2 class="font-bold text-gray-800">Student Grades</h2>
                        <span class="text-xs text-gray-400">{{ $subject->enrollments->count() }} students enrolled</span>
                    </div>

                    @if($subject->enrollments->isEmpty())
                        <div class="p-8 text-center text-gray-400">No students enrolled in this subject.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Midterm<br><span class="font-normal normal-case text-gray-400">(max 40)</span></th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Final<br><span class="font-normal normal-case text-gray-400">(max 60)</span></th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Grade</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">GPA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($subject->enrollments as $enrollment)
                                        @php
                                            $g = $enrollment->grade;
                                            $letter = $g?->letter_grade;
                                            $gc = match(true) {
                                                $letter === 'A'                  => ['#dcfce7','#15803d'],
                                                in_array($letter,['B+','B'])     => ['#dbeafe','#1d4ed8'],
                                                in_array($letter,['C+','C'])     => ['#fef9c3','#a16207'],
                                                $letter === 'D'                  => ['#fed7aa','#c2410c'],
                                                $letter === 'F'                  => ['#fee2e2','#dc2626'],
                                                default                          => ['#f3f4f6','#6b7280'],
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-gray-800 text-sm">{{ $enrollment->student->user->name }}</p>
                                                <p class="text-xs font-mono text-gray-400">{{ $enrollment->student->student_number }}</p>
                                            </td>

                                            {{-- Grade entry form --}}
                                            <form method="POST"
                                                  action="{{ $g ? route('admin.portal.grades.update', $g) : route('admin.portal.grades.store') }}"
                                                  class="contents">
                                                @csrf
                                                @if($g) @method('PUT') @endif
                                                @if(!$g)
                                                    <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                                @endif

                                                <td class="px-4 py-3 text-center">
                                                    <input type="number" name="midterm" step="0.5" min="0" max="40"
                                                           value="{{ $g?->midterm }}"
                                                           class="w-16 text-center border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                                           placeholder="0">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <input type="number" name="final" step="0.5" min="0" max="60"
                                                           value="{{ $g?->final }}"
                                                           class="w-16 text-center border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                                           placeholder="0">
                                                </td>
                                                <td class="px-4 py-3 text-center font-bold text-gray-800">
                                                    {{ $g?->total ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($letter)
                                                        <span class="w-9 h-9 rounded-xl inline-flex items-center justify-center text-sm font-bold"
                                                              style="background: {{ $gc[0] }}; color: {{ $gc[1] }};">{{ $letter }}</span>
                                                    @else
                                                        <span class="text-gray-400 text-sm">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center font-semibold text-gray-600 text-sm">
                                                    {{ $g?->gpa_points ? number_format($g->gpa_points, 2) : '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition hover:shadow"
                                                            style="background: #011C3E;">
                                                        {{ $g ? 'Update' : 'Save' }}
                                                    </button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
