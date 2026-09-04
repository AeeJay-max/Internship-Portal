@props([
    'route'      => '',
    'semesters'  => collect(),
    'semesterId' => null,
    'yearField'  => 'year_of_study',
    'yearLabel'  => 'Year',
    'programId'  => null,
    'extraFields'=> [],
])

<form method="GET" action="{{ $route }}"
      class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">

        {{-- Semester --}}
        <div class="min-w-56">
            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">Semester</label>
            <select name="semester_id" onchange="this.form.submit()"
                    class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" @selected($sem->id == $semesterId)>
                        {{ $sem->name }} — {{ $sem->academic_year }}
                        {{ $sem->is_current ? '(Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Year --}}
        <div class="min-w-32">
            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1.5">{{ $yearLabel }}</label>
            <select name="{{ $yearField }}" onchange="this.form.submit()"
                    class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:border-blue-400 transition appearance-none bg-white">
                <option value="">All Years</option>
                @foreach([1,2,3,4,5] as $y)
                    <option value="{{ $y }}" @selected(request($yearField) == $y)>Year {{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- Any extra hidden fields --}}
        @if($programId)
            <input type="hidden" name="program_id" value="{{ $programId }}">
        @endif
        @foreach($extraFields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach

        {{-- Active indicators --}}
        @php
            $activeSem = $semesters->firstWhere('id', $semesterId);
            $activeYear = request($yearField);
        @endphp
        <div class="ml-auto flex items-center gap-2">
            @if($activeSem?->is_current)
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                    ● Current Semester
                </span>
            @endif
            @if($activeYear)
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                    Year {{ $activeYear }}
                    <a href="{{ request()->fullUrlWithQuery([$yearField => '']) }}" class="opacity-60 hover:opacity-100">✕</a>
                </span>
            @endif
        </div>

    </div>
</form>
