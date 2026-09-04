@extends('application.layout')

@section('step-content')
<form method="POST" action="{{ route('application.motivation.store') }}" class="space-y-6">
    @csrf

    <div class="border-b border-slate-200 pb-4 mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Step 4 — Motivation Statement</h2>
        <p class="text-xs text-slate-500">Explain why you wish to undertake your internship attachment with the Ministry of Sport, Recreation, Arts & Culture.</p>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Motivation Statement *</label>
        <p class="text-xs text-slate-500 mb-2">Describe why you selected the Ministry, your background, and why you are a suitable candidate.</p>
        <textarea name="motivation_statement" rows="5" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Write your cover letter or motivation statement here...">{{ old('motivation_statement', $preference->motivation_statement ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Career Objectives</label>
            <textarea name="career_objectives" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Where do you see your career in 3-5 years?">{{ old('career_objectives', $preference->career_objectives ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Internship Objectives & Skills to Develop</label>
            <textarea name="skills_to_develop" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="What practical skills do you hope to gain during your Ministry placement?">{{ old('skills_to_develop', $preference->skills_to_develop ?? '') }}</textarea>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('application.preferences') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            &larr; Back to Preferences
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
            Save & Continue to Documents &rarr;
        </button>
    </div>
</form>
@endsection
