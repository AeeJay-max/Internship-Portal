@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <div class="relative py-24 overflow-hidden" style="background: linear-gradient(135deg, #011627 0%, #011C3E 100%);">
        <div class="absolute inset-0 opacity-10" style="background-image: url('/images/hero/MOSRAC-hero.jpg'); background-size: cover; background-position: center;"></div>
        <div class="relative max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: #90caf9;">Government of Zimbabwe</p>
            <h1 class="text-5xl font-bold text-white mb-4" style="font-family: 'Georgia', serif;">About MoSRAC</h1>
            <div class="w-16 h-1 mx-auto rounded mb-6" style="background: #611818;"></div>
            <p class="text-lg max-w-3xl mx-auto text-slate-200">
                Empowering Zimbabwean youth, athletes, artists, and cultural icons through structured national development and official Ministry internship attachments.
            </p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div style="background: #011C3E;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/20">
                <div class="py-6 text-center">
                    <p class="text-3xl font-extrabold text-white">2018</p>
                    <p class="text-xs mt-1 text-slate-300 font-semibold uppercase tracking-wider">Year Formed</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-3xl font-extrabold text-white">10</p>
                    <p class="text-xs mt-1 text-slate-300 font-semibold uppercase tracking-wider">Provinces Covered</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-3xl font-extrabold text-white">1,000+</p>
                    <p class="text-xs mt-1 text-slate-300 font-semibold uppercase tracking-wider">Interns Placed</p>
                </div>
                <div class="py-6 text-center">
                    <p class="text-3xl font-extrabold text-white">50+</p>
                    <p class="text-xs mt-1 text-slate-300 font-semibold uppercase tracking-wider">Partner Institutions</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Mandate & Background --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                        <img src="/images/hero/MOSRAC-hero.jpg" alt="MoSRAC Headquarters" class="w-full h-80 object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-44 h-44 rounded-2xl shadow-lg flex flex-col items-center justify-center text-white p-4 text-center" style="background:#011C3E;">
                        <p class="text-4xl font-extrabold" style="font-family:'Georgia',serif;">2018</p>
                        <p class="text-xs mt-1 text-slate-300 uppercase tracking-wider font-semibold">Established</p>
                    </div>
                </div>
                <div class="md:pl-6">
                    <p class="text-sm font-bold tracking-widest uppercase mb-3" style="color:#611818;">Ministry Overview</p>
                    <h2 class="text-4xl font-bold mb-6" style="color:#011C3E;font-family:'Georgia',serif;">Our Origin & Purpose</h2>
                    <div class="w-16 h-1 rounded mb-8" style="background:#611818;"></div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Formed in <strong>2018</strong>, the <strong>Ministry of Sport, Recreation, Arts and Culture (MoSRAC)</strong> is tasked with driving Zimbabwe's national strategy for sports development, artistic excellence, cultural preservation, and youth empowerment.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Through our official Internship Portal, MoSRAC offers students and recent tertiary graduates hands-on experience, professional mentorship, and practical attachments across key Government departments at Head Office in Harare and provincial offices nationwide.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        We remain committed to facilitating skill acquisition, fostering national pride, and building sustainable career pathways in sport, creative arts, and heritage administration.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Vision Values --}}
    <section class="py-20" style="background:#f0f4f8;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Strategic Direction</p>
                <h2 class="text-4xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;">Mission, Vision & Core Values</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded" style="background:#611818;"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#eff6ff;">
                        <svg class="w-6 h-6" fill="none" stroke="#011C3E" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        To formulate, implement, and coordinate policies that promote sports excellence, vibrant arts, cultural heritage, and structured youth internship opportunities across Zimbabwe.
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#f5f3ff;">
                        <svg class="w-6 h-6" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        To build a healthy, creative, and culturally vibrant nation powered by skilled youth and competitive sports and arts industries.
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6" style="background:#fef3c7;">
                        <svg class="w-6 h-6" fill="none" stroke="#b45309" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4" style="color:#011C3E;font-family:'Georgia',serif;">Core Values</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Integrity, inclusivity, transparency, cultural identity, professional excellence, and commitment to national youth empowerment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Ministry Key Departments --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color:#611818;">Ministry Structure</p>
                <h2 class="text-4xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;">Key Ministry Departments</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded" style="background:#611818;"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['Department of Sport Development & Recreation','Manages national sports federations, athlete development programs, and community recreation.','/images/programs/engineering.jpg'],
                    ['Department of Arts & Culture Promotion','Drives the creative economy, cultural festivals, music, performing arts, and fine arts promotion.','/images/programs/it.jpg'],
                    ['Department of Heritage & Cultural Preservation','Preserves historical sites, monuments, museum artifacts, and tangible/intangible Zimbabwean heritage.','/images/programs/architecture.jpg'],
                    ['Department of Youth & Athlete Empowerment','Focuses on skill building, mentorship, internship placement, and career development for young leaders.','/images/programs/engineering.jpg'],
                    ['Department of Policy, Planning & Research','Conducts strategic research, policy formulation, performance monitoring, and international relations.','/images/programs/it.jpg'],
                    ['Department of Finance & Administration','Provides administrative support, human resources management, public relations, and financial oversight.','/images/programs/architecture.jpg'],
                ] as $dept)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="h-32 bg-cover bg-center relative overflow-hidden" style="background-image:url('{{ $dept[2] }}');">
                                <div class="absolute inset-0" style="background:rgba(1,28,62,0.75);"></div>
                                <div class="absolute inset-0 flex items-end p-4">
                                    <h3 class="text-white font-bold text-sm leading-tight" style="font-family:'Georgia',serif;">{{ $dept[0] }}</h3>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $dept[1] }}</p>
                            </div>
                        </div>
                        <div class="px-5 pb-5 pt-0">
                            <a href="{{ route('opportunities.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold hover:underline" style="color:#611818;">
                                View Open Internships
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
            <h2 class="text-3xl font-bold text-white mb-4" style="font-family:'Georgia',serif;">Apply for a MoSRAC Internship</h2>
            <p class="mb-8" style="color:rgba(255,255,255,0.75);">Gain practical experience and contribute to Zimbabwe's sport, arts, and cultural development.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('application.selectType') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold transition-all hover:shadow-lg" style="background:white;color:#011C3E;">
                    Start Internship Application <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('opportunities.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold border border-white/30 text-white hover:bg-white/10 transition-all">
                    Explore Vacancies
                </a>
            </div>
        </div>
    </div>

@endsection
