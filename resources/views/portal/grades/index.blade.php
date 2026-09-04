@extends('portal.layouts.app')

@section('content')

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-xl font-bold" style="color: #011C3E;">Grades</h1>
            <p class="text-xs text-gray-400 mt-0.5">Academic performance</p>
        </div>
        <a href="{{ route('portal.transcript') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-white shadow-sm"
           style="background: #011C3E;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Transcript
        </a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium">Cumul. GPA</p>
            <p class="text-3xl font-bold mt-1" style="color: #011C3E;">{{ $cumulativeGpa ? number_format($cumulativeGpa, 2) : 'N/A' }}</p>
            <div class="mt-2 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                <div class="h-full rounded-full" style="width: {{ min(100, ($cumulativeGpa / 4) * 100) }}%; background: #011C3E;"></div>
            </div>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium">Credits</p>
            <p class="text-3xl font-bold mt-1" style="color: #7c3aed;">{{ $totalCredits }}</p>
            <p class="text-xs text-gray-400 mt-1">enrolled</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium">Standing</p>
            @php $sc = match(true) { str_contains($standing,'Excellent') => ['#dcfce7','#15803d'], str_contains($standing,'Good') => ['#dbeafe','#1d4ed8'], str_contains($standing,'Satisfactory') => ['#fef9c3','#a16207'], default => ['#fee2e2','#dc2626'] }; @endphp
            <div class="mt-2 px-2 py-1.5 rounded-lg text-xs font-bold inline-block" style="background: {{ $sc[0] }}; color: {{ $sc[1] }};">
                {{ $standing }}
            </div>
        </div>
    </div>

    {{-- Per Semester --}}
    @forelse($semesterData as $data)
        <div class="card mb-4 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between" style="background: #f8fafc;">
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">{{ $data['semester']->name }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $data['credits'] }} credits</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">GPA</p>
                    <p class="text-2xl font-bold" style="color: #011C3E;">{{ $data['gpa'] ? number_format($data['gpa'], 2) : 'N/A' }}</p>
                </div>
            </div>

            {{-- Mobile: card per subject --}}
            <div class="divide-y divide-gray-50 md:hidden">
                @foreach($data['enrollments'] as $enrollment)
                    @php
                        $g = $enrollment->grade;
                        $letter = $g?->letter_grade;
                        $gc = match(true) { $letter==='A' => ['#dcfce7','#15803d'], in_array($letter,['B+','B']) => ['#dbeafe','#1d4ed8'], in_array($letter,['C+','C']) => ['#fef9c3','#a16207'], $letter==='D' => ['#fed7aa','#c2410c'], $letter==='F' => ['#fee2e2','#dc2626'], default => ['#f3f4f6','#9ca3af'] };
                    @endphp
                    <div class="px-4 py-3.5 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $enrollment->subject->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <span class="font-mono">{{ $enrollment->subject->code }}</span>
                                @if($g) · {{ $g->midterm }}/40 + {{ $g->final }}/60 = {{ $g->total }}/100 @endif
                            </p>
                        </div>
                        @if($letter)
                            <div class="shrink-0 text-center">
                            <span class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold"
                                  style="background: {{ $gc[0] }}; color: {{ $gc[1] }};">{{ $letter }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ number_format($g->gpa_points, 1) }}</p>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">In progress</span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr style="background: #f8fafc;">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Subject</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Cr.</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Mid /40</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Final /60</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Total</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">Grade</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase border-b border-gray-100">GPA</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                    @foreach($data['enrollments'] as $enrollment)
                        @php $g = $enrollment->grade; $letter = $g?->letter_grade; $gc = match(true) { $letter==='A' => ['#dcfce7','#15803d'], in_array($letter,['B+','B']) => ['#dbeafe','#1d4ed8'], in_array($letter,['C+','C']) => ['#fef9c3','#a16207'], $letter==='D' => ['#fed7aa','#c2410c'], $letter==='F' => ['#fee2e2','#dc2626'], default => ['#f3f4f6','#9ca3af'] }; @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3">
                                <p class="font-semibold text-gray-800">{{ $enrollment->subject->name }}</p>
                                <p class="text-xs font-mono text-gray-400">{{ $enrollment->subject->code }}</p>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $enrollment->subject->credits }}</td>
                            @if($g)
                                <td class="px-4 py-3 text-center font-medium text-gray-700">{{ $g->midterm }}</td>
                                <td class="px-4 py-3 text-center font-medium text-gray-700">{{ $g->final }}</td>
                                <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $g->total }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($letter)
                                        <span class="w-9 h-9 rounded-xl inline-flex items-center justify-center text-sm font-bold"
                                              style="background: {{ $gc[0] }}; color: {{ $gc[1] }};">{{ $letter }}</span>
                                    @else <span class="text-gray-400">—</span> @endif
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-600">{{ $g->gpa_points ? number_format($g->gpa_points, 2) : '—' }}</td>
                            @else
                                <td colspan="5" class="px-4 py-3 text-center text-gray-400">In Progress</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr style="background: #f0f4f8;">
                        <td class="px-5 py-2.5 text-xs font-bold text-gray-700">Semester Total</td>
                        <td class="px-4 py-2.5 text-center text-xs font-bold text-gray-800">{{ $data['credits'] }}</td>
                        <td colspan="4" class="px-4 py-2.5"></td>
                        <td class="px-4 py-2.5 text-center text-xs font-bold" style="color: #011C3E;">{{ $data['gpa'] ? number_format($data['gpa'], 2) : '—' }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @empty
        <div class="card p-12 text-center text-gray-400 text-sm">No grades recorded yet.</div>
    @endforelse

@endsection
