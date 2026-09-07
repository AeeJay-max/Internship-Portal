@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <div class="relative py-16 overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-bottom: 4px solid #f59e0b;">
        <div class="max-w-7xl mx-auto px-6 text-center text-white relative z-10">
            <p class="text-xs font-extrabold uppercase tracking-widest text-amber-300 mb-2">Application Guide</p>
            <h1 class="text-4xl font-extrabold mb-3 text-white">How to Apply for an Internship</h1>
            <p class="text-sm max-w-2xl mx-auto text-slate-200">
                Follow this simple step-by-step process to submit your internship application to the Ministry of Sport, Recreation, Arts & Culture.
            </p>
        </div>
    </div>

    {{-- Main Guide Container --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-10">

            {{-- Crucial Single PDF Warning Card --}}
            <div class="p-6 rounded-2xl bg-amber-500/10 border-2 border-amber-500 text-amber-900 shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-emerald-950 flex items-center justify-center font-black text-xl shrink-0">
                    !
                </div>
                <div class="space-y-1 text-xs sm:text-sm">
                    <h3 class="font-extrabold text-amber-950 text-base uppercase tracking-wide">Important Document Requirement</h3>
                    <p class="text-amber-900 leading-relaxed font-semibold">
                        All required supporting documents MUST be combined into <strong>ONE single PDF file</strong> before uploading.
                    </p>
                    <p class="text-amber-800 text-xs">
                        Do not upload documents separately. The portal only accepts <strong>1 single PDF file</strong> containing your ID, Academic Results, Current Results, CV, and University Letter.
                    </p>
                </div>
            </div>

            {{-- 8-Step Process Grid --}}
            <div class="space-y-6">
                <h2 class="text-2xl font-black text-slate-900 text-center uppercase tracking-wide">8-Step Application Process</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Step 1 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            1
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Create / Login to Account</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Register a new account or log into your existing applicant account using your email address and password.
                            </p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            2
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Start Internship Application</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Click "Apply for Internship" to launch the multi-step application wizard.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            3
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Select Preferred Department</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Choose your primary and secondary preferred Ministry department (e.g. Sport Development, Arts Promotion, Heritage, Youth Empowerment).
                            </p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            4
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Prepare Required Documents</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Gather all 5 required supporting documents: National ID, Academic Results, Current University Results, CV, and University Internship Letter.
                            </p>
                        </div>
                    </div>

                    {{-- Step 5 --}}
                    <div class="bg-white p-6 rounded-2xl border-2 border-emerald-600 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-extrabold text-base shrink-0">
                            5
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Combine into ONE PDF</h4>
                            <p class="text-xs text-slate-600 leading-relaxed font-semibold">
                                Use a free PDF merger tool or mobile scanner app to merge all 5 documents into <strong>a single PDF file</strong> (under 10MB).
                            </p>
                        </div>
                    </div>

                    {{-- Step 6 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            6
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Upload Single PDF</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Upload your combined PDF file in Step 5 of the application wizard.
                            </p>
                        </div>
                    </div>

                    {{-- Step 7 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            7
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Review & Submit</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Verify your personal details, academic information, and uploaded PDF on the final review page before submitting.
                            </p>
                        </div>
                    </div>

                    {{-- Step 8 --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-base shrink-0">
                            8
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 text-base">Track Application Status</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Log into your portal dashboard to monitor review progress, interview requests, and placement assignment.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Required PDF Checklist Summary --}}
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    Mandatory 5-in-1 PDF Document Checklist
                </h3>
                <p class="text-xs text-slate-600">Ensure your single uploaded PDF contains the following 5 items in order:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold text-slate-700">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">1.</span> Clear Copy of National ID / Passport
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">2.</span> Academic Results & Transcripts
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">3.</span> Current University Results
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">4.</span> Curriculum Vitae (CV)
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-2 sm:col-span-2">
                        <span class="text-emerald-600 font-bold">5.</span> Official University Internship Letter / Recommendation
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="text-center pt-4">
                <a href="{{ route('apply.start') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition">
                    Ready to Apply? Start Application Now &rarr;
                </a>
            </div>

        </div>
    </section>

@endsection
