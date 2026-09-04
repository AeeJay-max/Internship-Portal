@extends('application.layout')

@section('step-content')
<form method="POST" action="{{ route('application.preferences.store') }}" class="space-y-6">
    @csrf

    <div class="border-b border-slate-200 pb-4 mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Step 3 — Internship Preferences</h2>
        <p class="text-xs text-slate-500">Select your preferred Ministry department, placement dates, and duration.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Preferred Ministry Department *</label>
            <select name="preferred_department_id" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Select Preferred Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('preferred_department_id', $preference->preferred_department_id ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }} ({{ $dept->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Second Preferred Department (Optional)</label>
            <select name="second_preferred_department_id" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Select Second Choice --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('second_preferred_department_id', $preference->second_preferred_department_id ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }} ({{ $dept->code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Area of Specialisation</label>
            <input type="text" name="specialisation" value="{{ old('specialisation', $preference->specialisation ?? '') }}" placeholder="e.g. Web Development, Sports Coaching, Cultural Archiving" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Preferred Placement Location</label>
            <input type="text" name="preferred_location" value="{{ old('preferred_location', $preference->preferred_location ?? 'Harare') }}" placeholder="e.g. Harare HQ, Bulawayo Regional Office" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Preferred Start Date *</label>
            <input type="date" name="preferred_start_date" value="{{ old('preferred_start_date', optional($preference->preferred_start_date ?? null)->format('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Preferred End Date *</label>
            <input type="date" name="preferred_end_date" value="{{ old('preferred_end_date', optional($preference->preferred_end_date ?? null)->format('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Required Internship Duration *</label>
            <select name="required_duration" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Duration</option>
                <option value="3 Months" {{ old('required_duration', $preference->required_duration ?? '') == '3 Months' ? 'selected' : '' }}>3 Months</option>
                <option value="6 Months" {{ old('required_duration', $preference->required_duration ?? '') == '6 Months' ? 'selected' : '' }}>6 Months</option>
                <option value="8 Months" {{ old('required_duration', $preference->required_duration ?? '') == '8 Months' ? 'selected' : '' }}>8 Months</option>
                <option value="12 Months" {{ old('required_duration', $preference->required_duration ?? '') == '12 Months' ? 'selected' : '' }}>12 Months (Full Academic Year)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Flexibility to Work in Another Department *</label>
            <select name="flexible_department" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="1" {{ old('flexible_department', $preference->flexible_department ?? 1) == 1 ? 'selected' : '' }}>Yes — I am flexible to be placed in another department</option>
                <option value="0" {{ old('flexible_department', $preference->flexible_department ?? 1) == 0 ? 'selected' : '' }}>No — I only prefer my selected department(s)</option>
            </select>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.academic') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Academic
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
            Save & Continue to Motivation &rarr;
        </button>
    </div>
</form>
@endsection
