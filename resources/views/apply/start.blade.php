@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <div class="relative py-20 overflow-hidden" style="background: linear-gradient(135deg, #011627 0%, #011C3E 100%);">
        <img src="{{ asset('images/branding/MOSRAC.svg') }}" alt=""
             class="absolute pointer-events-none select-none opacity-5"
             style="width:420px; right:4%; top:50%; transform:translateY(-50%); filter:brightness(0) invert(1);">
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <span class="inline-block text-xs font-bold tracking-widest uppercase px-3 py-1.5 rounded-full mb-5"
                  style="background:rgba(97,24,24,0.5); color:rgba(255,255,255,0.85);">
                Admissions 2025–2026
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-5" style="font-family:'Georgia',serif;">
                Begin Your Journey at MOSRAC
            </h1>
            <p class="text-lg max-w-xl mx-auto" style="color:rgba(255,255,255,0.75);">
                Apply online for Internship at the Ministry of Sport, Recreation, Arts & Culture.
            </p>
        </div>
    </div>

    {{-- Main content --}}
    <div class="py-16" style="background:#f0f4f8;">
        <div class="max-w-5xl mx-auto px-6">

            {{-- Two action cards --}}
            <div class="grid md:grid-cols-2 gap-6 mb-14">

                {{-- New applicant --}}
                <div class="bg-white rounded-2xl shadow border border-gray-100 p-8 flex flex-col">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6" style="background:#eff6ff;">
                        <svg class="w-7 h-7" fill="none" stroke="#1d4ed8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold mb-2" style="color:#011C3E; font-family:'Georgia',serif;">New Applicant</h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6 flex-1">
                        Don't have an account yet? Create one to start your application. The process takes about 20–30 minutes.
                    </p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold text-white transition hover:shadow-lg hover:-translate-y-0.5"
                       style="background:#011C3E;">
                        Create Account & Apply
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                {{-- Returning applicant --}}
                <div class="bg-white rounded-2xl shadow border border-gray-100 p-8 flex flex-col">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6" style="background:#f5f3ff;">
                        <svg class="w-7 h-7" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold mb-2" style="color:#011C3E; font-family:'Georgia',serif;">Already Have an Account?</h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6 flex-1">
                        Sign in to continue your existing application or check the status of a submitted one.
                    </p>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold border-2 transition hover:-translate-y-0.5"
                       style="border-color:#011C3E; color:#011C3E;">
                        Sign In to Continue
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

            </div>

            {{-- How it works --}}
            <div class="mb-14">
                <h2 class="text-2xl font-bold text-center mb-2" style="color:#011C3E; font-family:'Georgia',serif;">How the Application Works</h2>
                <p class="text-center text-gray-400 text-sm mb-10">6 simple steps from registration to acceptance</p>

                <div class="grid md:grid-cols-3 gap-4">
                    @foreach([
                        ['01', 'Create Account',       'Register with your email and verify your address.',                                    '#eff6ff','#1d4ed8'],
                        ['02', 'Choose Program',       'Select your degree level and the program you want to apply for.',                      '#f0fdf4','#15803d'],
                        ['03', 'Personal Information', 'Fill in your personal, academic, and family details.',                                 '#fff7ed','#c2410c'],
                        ['04', 'Upload Documents',     'Submit your transcripts, passport, and other required documents.',                     '#f5f3ff','#7c3aed'],
                        ['05', 'Review & Submit',      'Review your entire application before final submission.',                              '#fef3c7','#b45309'],
                        ['06', 'Wait for Decision',    'Our admissions team reviews your application and notifies you by email.',              '#fdf4ff','#7e22ce'],
                    ] as [$num, $title, $desc, $bg, $color])
                        <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold"
                                 style="background:{{ $bg }}; color:{{ $color }}">
                                {{ $num }}
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1" style="color:#011C3E;">{{ $title }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Requirements + Deadlines --}}
            <div class="grid md:grid-cols-2 gap-6 mb-14">

                {{-- Requirements --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2" style="color:#011C3E;">
                        <svg class="w-5 h-5" fill="none" stroke="#611818" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        What You'll Need
                    </h3>
                    <ul class="space-y-2.5 text-sm text-gray-600">
                        @foreach([
                            'Valid passport or national ID',
                            'Official school / university transcripts',
                            'High school or bachelor\'s diploma',
                            'Passport-size photo',
                            'Two letters of recommendation (Master\'s / PhD)',
                            'Statement of purpose (Master\'s / PhD)',
                            'Language proficiency certificate (if applicable)',
                        ] as $req)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $req }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Key dates --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2" style="color:#011C3E;">
                        <svg class="w-5 h-5" fill="none" stroke="#611818" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Key Dates — Fall 2026
                    </h3>
                    <div class="space-y-3">
                        @foreach([
                            ['Applications Open',       'March 1, 2026',    'green'],
                            ['Application Deadline',    'July 15, 2026',    'orange'],
                            ['Decisions Sent',          'August 1, 2026',   'blue'],
                            ['Enrollment Deadline',     'August 20, 2026',  'purple'],
                            ['Semester Begins',         'September 1, 2026','navy'],
                        ] as [$event, $date, $c])
                            @php
                                $colors = ['green'=>['#f0fdf4','#15803d'],'orange'=>['#fff7ed','#c2410c'],'blue'=>['#eff6ff','#1d4ed8'],'purple'=>['#f5f3ff','#7c3aed'],'navy'=>['#eff6ff','#011C3E']];
                                [$bg, $fg] = $colors[$c];
                            @endphp
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                <span class="text-sm text-gray-600">{{ $event }}</span>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                      style="background:{{ $bg }}; color:{{ $fg }}">{{ $date }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Bottom CTA --}}
            <div class="text-center">
                <p class="text-gray-400 text-sm mb-4">Questions? Contact our admissions team</p>
                <div class="flex items-center justify-center gap-6 flex-wrap">
                    <a href="mailto:info@mosrac.gov.zw"
                       class="inline-flex items-center gap-2 text-sm font-semibold transition"
                       style="color:#611818;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        info@mosrac.gov.zw
                    </a>
                    <a href="tel:+37410583841"
                       class="inline-flex items-center gap-2 text-sm font-semibold transition"
                       style="color:#611818;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        +263 24 2708373
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
