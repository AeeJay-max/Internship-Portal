@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <div class="relative overflow-hidden text-white min-h-[45vh] lg:min-h-[50vh] flex items-center py-12 sm:py-16 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/branding/heroes_acre.jpg') }}'); background-position: center 30%;">
        
        {{-- Semi-Transparent MoSRAC Green Overlay --}}
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(0, 80, 38, 0.90) 0%, rgba(0, 60, 28, 0.88) 55%, rgba(10, 18, 12, 0.92) 100%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="max-w-2xl text-left space-y-4">
                
                {{-- Institutional Label --}}
                <div class="inline-flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-amber-400 rounded-full"></span>
                    <p class="text-xs sm:text-sm font-bold tracking-widest text-amber-300 uppercase">
                        APPLICATION GUIDE & PROCEDURE
                    </p>
                </div>

                {{-- Main Heading --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-sm">
                    How to Apply for an Internship
                </h1>

                {{-- Supporting Description --}}
                <p class="text-base sm:text-lg text-white font-normal leading-relaxed max-w-xl">
                    Follow this official step-by-step guide to prepare your documents and submit an internship application to the Ministry of Sport, Recreation, Arts & Culture.
                </p>

            </div>
        </div>
    </div>

    {{-- Main Content Section --}}
    <section class="py-12 lg:py-16 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-10">

            {{-- Official Document Requirement Notice --}}
            <div class="bg-amber-50 border-l-4 border-l-amber-500 border-t border-r border-b border-amber-200/80 rounded-r-md p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded bg-amber-500 text-slate-950 flex items-center justify-center font-extrabold text-base shrink-0 mt-0.5">
                        !
                    </div>
                    <div class="space-y-1.5 text-xs sm:text-sm">
                        <h3 class="font-bold text-amber-950 text-sm sm:text-base uppercase tracking-wide">
                            IMPORTANT DOCUMENT REQUIREMENT
                        </h3>
                        <p class="text-amber-900 leading-relaxed font-semibold">
                            All required supporting documents MUST be combined into <strong>ONE single PDF file</strong> before uploading.
                        </p>
                        <p class="text-amber-800/90 leading-relaxed text-xs">
                            Do not upload documents separately. The portal only accepts <strong>1 single PDF file</strong> (under 10MB) containing your National ID, Academic Transcripts, Current University Results, CV, and University Recommendation Letter.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 8-Step Application Process (Vertical Institutional Timeline) --}}
            <div class="space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-[#005A2B]"></span>
                        <span class="text-xs font-bold text-[#005A2B] uppercase tracking-wider">Step-by-step guide</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                        8-Step Application Process
                    </h2>
                </div>

                {{-- Clean Vertical Process List --}}
                <div class="bg-white border border-slate-200/90 rounded-md divide-y divide-slate-200 shadow-sm">
                    
                    {{-- Step 1 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            01
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Create / Login to Account</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Register a new account or log into your existing applicant account using your email address and password.
                            </p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            02
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Start Internship Application</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Click "Apply for Internship" on the homepage or portal navigation to launch the application wizard.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            03
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Select Preferred Department</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Choose your primary and secondary preferred Ministry department (e.g. Sport Development, Arts Promotion, Heritage, Recreation).
                            </p>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            04
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Prepare Required Documents</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Gather all 5 required supporting documents: National ID, Academic Results, Current University Results, CV, and University Internship Letter.
                            </p>
                        </div>
                    </div>

                    {{-- Step 5 (Highlighted Requirement) --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5 bg-amber-50/50 border-l-4 border-l-amber-500">
                        <div class="w-10 h-10 rounded bg-amber-400 text-slate-950 font-bold text-sm flex items-center justify-center shrink-0">
                            05
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-slate-900">Combine into ONE PDF</h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-200 text-amber-900">Critical</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">
                                Use a PDF merger tool or mobile scanner app to merge all 5 documents into <strong>a single PDF file</strong> (under 10MB).
                            </p>
                        </div>
                    </div>

                    {{-- Step 6 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            06
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Upload Single PDF</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Upload your combined PDF file in Step 5 of the online application wizard.
                            </p>
                        </div>
                    </div>

                    {{-- Step 7 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            07
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Review & Submit</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Verify your personal details, academic information, and uploaded PDF on the final review page before submitting.
                            </p>
                        </div>
                    </div>

                    {{-- Step 8 --}}
                    <div class="p-5 sm:p-6 flex items-start gap-5">
                        <div class="w-10 h-10 rounded bg-[#005A2B]/10 text-[#005A2B] font-bold text-sm flex items-center justify-center shrink-0">
                            08
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Track Application Status</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Log into your portal dashboard to monitor review progress, interview requests, and placement assignment by Ministry officers.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Mandatory 5-in-1 PDF Document Checklist --}}
            <div class="bg-white border border-slate-200/90 rounded-md p-6 sm:p-7 shadow-sm border-t-4 border-[#005A2B] space-y-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 uppercase tracking-wide mb-1">
                        Mandatory 5-in-1 PDF Document Checklist
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600">
                        Ensure your single uploaded PDF file contains the following 5 documents in exact order:
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm font-medium text-slate-800">
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded flex items-center gap-3">
                        <span class="w-6 h-6 rounded bg-[#005A2B] text-white flex items-center justify-center font-bold text-xs shrink-0">1</span>
                        <span>Clear Copy of National ID / Passport</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded flex items-center gap-3">
                        <span class="w-6 h-6 rounded bg-[#005A2B] text-white flex items-center justify-center font-bold text-xs shrink-0">2</span>
                        <span>Academic Results & Transcripts</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded flex items-center gap-3">
                        <span class="w-6 h-6 rounded bg-[#005A2B] text-white flex items-center justify-center font-bold text-xs shrink-0">3</span>
                        <span>Current University Results</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded flex items-center gap-3">
                        <span class="w-6 h-6 rounded bg-[#005A2B] text-white flex items-center justify-center font-bold text-xs shrink-0">4</span>
                        <span>Curriculum Vitae (CV)</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded flex items-center gap-3 sm:col-span-2">
                        <span class="w-6 h-6 rounded bg-[#005A2B] text-white flex items-center justify-center font-bold text-xs shrink-0">5</span>
                        <span>Official University Internship Letter / Recommendation</span>
                    </div>
                </div>
            </div>

            {{-- Primary Call To Action --}}
            <div class="text-center pt-2">
                <a href="{{ route('apply.start') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-sm uppercase tracking-wider rounded-md shadow-md hover:shadow-lg transition-all">
                    Ready to Apply? Start Application Now &rarr;
                </a>
            </div>

        </div>
    </section>

@endsection

