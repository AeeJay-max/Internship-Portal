@extends('application.layout')

@section('step-content')
<form method="POST" action="{{ route('application.academic.store') }}" class="space-y-6">
    @csrf

    <div class="border-b border-slate-200 pb-4 mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Step 2 — Academic Background</h2>
        <p class="text-xs text-slate-500">Provide information regarding your education and current studies.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Institution Name *</label>
            <input type="text" name="school_name" value="{{ old('school_name', $academic->school_name ?? '') }}" required placeholder="e.g. University of Zimbabwe, Chinhoyi University of Technology" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Institution Type *</label>
            <select name="institution_type" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Institution Type</option>
                <option value="University" {{ old('institution_type', $academic->institution_type ?? '') == 'University' ? 'selected' : '' }}>University</option>
                <option value="Polytechnic" {{ old('institution_type', $academic->institution_type ?? '') == 'Polytechnic' ? 'selected' : '' }}>Polytechnic</option>
                <option value="College" {{ old('institution_type', $academic->institution_type ?? '') == 'College' ? 'selected' : '' }}>College</option>
                <option value="Vocational Center" {{ old('institution_type', $academic->institution_type ?? '') == 'Vocational Center' ? 'selected' : '' }}>Vocational Center</option>
                <option value="Other" {{ old('institution_type', $academic->institution_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Programme / Course of Study *</label>
            <input type="text" name="program_of_study" value="{{ old('program_of_study', $academic->program_of_study ?? '') }}" required placeholder="e.g. BSc Computer Science, Diploma in Sports Management" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Field of Study *</label>
            <input type="text" name="field_of_study" value="{{ old('field_of_study', $academic->field_of_study ?? '') }}" required placeholder="e.g. Information Technology, Public Relations, Fine Arts" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Level / Year *</label>
            <select name="current_year_level" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Level</option>
                <option value="Year 1" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 1' ? 'selected' : '' }}>Year 1</option>
                <option value="Year 2" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 2' ? 'selected' : '' }}>Year 2</option>
                <option value="Year 3" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 3' ? 'selected' : '' }}>Year 3</option>
                <option value="Year 4" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Year 4' ? 'selected' : '' }}>Year 4</option>
                <option value="Graduated" {{ old('current_year_level', $academic->current_year_level ?? '') == 'Graduated' ? 'selected' : '' }}>Graduated / Postgraduate</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Qualification Pursued / Obtained *</label>
            <input type="text" name="academic_qualification" value="{{ old('academic_qualification', $academic->academic_qualification ?? '') }}" required placeholder="e.g. Bachelor Degree, National Diploma, Master Degree" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expected Graduation Date</label>
            <input type="date" name="expected_graduation_date" value="{{ old('expected_graduation_date', optional($academic->expected_graduation_date ?? null)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Academic Performance / GPA (Optional)</label>
        <input type="text" name="gpa" value="{{ old('gpa', $academic->gpa ?? '') }}" placeholder="e.g. 3.5 GPA or 2.1 Pass Class" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.personal') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Personal
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
            Save & Continue to Preferences &rarr;
        </button>
    </div>
</form>
@endsection
