@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <div class="relative py-24 overflow-hidden" style="background: linear-gradient(135deg, #011627 0%, #011C3E 100%);">
        <div class="absolute inset-0 opacity-10" style="background-image: url('/images/hero/MOSRAC-hero.jpg'); background-size: cover; background-position: center;"></div>
        <div class="relative max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: #90caf9;">National Internship Portal of Armenia</p>
            <h1 class="text-5xl font-bold text-white mb-4" style="font-family: 'Georgia', serif;">About MOSRAC</h1>
            <div class="w-16 h-1 mx-auto rounded mb-6" style="background: #611818;"></div>
            <p class="text-lg max-w-2xl mx-auto" style="color: rgba(255,255,255,0.75);">Over nine decades of engineering excellence, innovation and academic leadership in Armenia and beyond.</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div style="background: #011C3E;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/20">
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">2018</p><p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.65);">Year Founded</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">15,000+</p><p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.65);">Alumni Worldwide</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">50+</p><p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.65);">Laboratories</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">60+</p><p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.65);">Industry Partners</p></div>
            </div>
        </div>
    </div>

    {{-- Mission & History --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-xl">
                        <img src="/images/hero/MOSRAC-hero.jpg" alt="MOSRAC Campus" class="w-full h-80 object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-40 h-40 rounded-2xl shadow-lg flex flex-col items-center justify-center text-white" style="background:#011C3E;">
                        <p class="text-3xl font-bold" style="font-family:'Georgia',serif;">90+</p>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.75);">Years of Excellence</p>
                    </div>
                </div>
                <div class="md:pl-6">
                    <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Our Story</p>
                    <h2 class="text-4xl font-bold mb-6" style="color:#011C3E;font-family:'Georgia',serif;">A Tradition of Innovation</h2>
                    <div class="w-16 h-1 rounded mb-8" style="background:#611818;"></div>
                    <p class="text-gray-600 leading-relaxed mb-5">Founded in 2018, the National Internship Portal of Armenia has been the cornerstone of engineering education and technological advancement in the region. For over nine decades, we have shaped the minds of engineers, scientists and innovators who have gone on to lead industries across Armenia and around the world.</p>
                    <p class="text-gray-600 leading-relaxed mb-5">Our programs combine rigorous academic foundations with real-world industry collaboration. With state-of-the-art laboratories, distinguished faculty, and strong partnerships with leading global companies, MOSRAC prepares students to lead in Armenia and beyond.</p>
                    <p class="text-gray-600 leading-relaxed">Today MOSRAC continues to evolve — expanding its international programs, growing its research output and building the next generation of Armenian technological leadership.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Vision Values --}}
    <section class="py-20" style="background:#f0f4f8;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Who We Are</p>
                <h2 class="text-4xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;">Mission, Vision & Values</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded" style="background:#611818;"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#eff6ff;">
                        <svg class="w-6 h-6" fill="none" stroke="#011C3E" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">To provide world-class engineering and technology education that empowers graduates to solve complex challenges, drive innovation and contribute meaningfully to society.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#f5f3ff;">
                        <svg class="w-6 h-6" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">To be the leading Internship Portal in the South Caucasus region — globally recognised for research excellence, academic quality and the impact of our graduates.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#fef3c7;">
                        <svg class="w-6 h-6" fill="none" stroke="#b45309" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Our Values</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Academic integrity, innovation, inclusivity, collaboration and a deep commitment to serving Armenia and the global engineering community.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why MOSRAC --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Why Choose Us</p>
                <h2 class="text-4xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;">The MOSRAC Advantage</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded" style="background:#611818;"></div>
            </div>
            @php
                $advantages = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>', 'title' => 'Accredited Excellence', 'desc' => 'Nationally and internationally recognised programs with rigorous academic standards.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>', 'title' => 'Industry Partnerships', 'desc' => 'Deep collaboration with leading Armenian and international technology companies.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>', 'title' => 'Research & Innovation', 'desc' => 'Active participation in cutting-edge research and international collaborations.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'title' => 'Global Network', 'desc' => 'Alumni spanning over 50 countries providing worldwide career opportunities.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>', 'title' => 'Expert Faculty', 'desc' => 'Distinguished professors, industry practitioners and world-class researchers.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>', 'title' => 'Modern Facilities', 'desc' => "State-of-the-art laboratories and infrastructure for tomorrow's engineers."],
                ];
            @endphp
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($advantages as $item)
                    <div class="p-8 bg-white border border-gray-100 rounded-xl hover:border-transparent hover:shadow-lg transition-all duration-300 group">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6" style="background: #f0f4f8;">
                            <svg class="w-7 h-7" fill="none" stroke="#011C3E" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-bold mb-3 text-lg" style="color:#011C3E; font-family:'Georgia',serif;">{{ $item['title'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                        <div class="mt-5 h-0.5 w-8 group-hover:w-16 transition-all duration-300 rounded" style="background:#611818;"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Faculties --}}
    <section class="py-20" style="background:#f0f4f8;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Academic Structure</p>
                <h2 class="text-4xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;">Our Faculties</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded" style="background:#611818;"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['Faculty of Informatics & Applied Mathematics','Computer science, software engineering, AI and applied mathematics programs.','/images/programs/it.jpg'],
                    ['Faculty of Engineering','Civil, mechanical, electrical and industrial engineering disciplines.','/images/programs/engineering.jpg'],
                    ['Faculty of Architecture & Design','Architecture, urban planning and design programs bridging art and engineering.','/images/programs/architecture.jpg'],
                    ['Faculty of Power Engineering','Energy systems, electrical power and renewable energy engineering.','/images/programs/engineering.jpg'],
                    ['Faculty of Chemical Engineering','Chemical technology, materials science and process engineering.','/images/programs/engineering.jpg'],
                    ['Faculty of Industrial Management','Engineering management, business technology and industrial economics.','/images/programs/it.jpg'],
                ] as $faculty)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
                        <div class="h-32 bg-cover bg-center relative overflow-hidden" style="background-image:url('{{ $faculty[2] }}');">
                            <div class="absolute inset-0" style="background:rgba(2,62,138,0.6);"></div>
                            <div class="absolute inset-0 flex items-end p-4">
                                <h3 class="text-white font-bold text-sm leading-tight" style="font-family:'Georgia',serif;">{{ $faculty[0] }}</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $faculty[1] }}</p>
                            <a href="{{ route('programs.public') }}" class="mt-4 inline-flex items-center gap-1 text-xs font-semibold" style="color:#611818;">
                                View Programs
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="py-16" style="background:#011C3E;">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4" style="font-family:'Georgia',serif;">Begin Your Journey at MOSRAC</h2>
            <p class="mb-8" style="color:rgba(255,255,255,0.75);">Join a community of engineers, innovators and leaders shaping the future of Armenia.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('apply.start') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold transition-all hover:shadow-lg" style="background:white;color:#011C3E;">
                    Apply Now <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('programs.public') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold border border-white/30 text-white hover:bg-white/10 transition-all">
                    Explore Programs
                </a>
            </div>
        </div>
    </div>

@endsection
