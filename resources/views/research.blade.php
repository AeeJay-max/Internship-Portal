@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <div class="relative py-24 overflow-hidden" style="background: linear-gradient(135deg, #00421F 0%, #005A2B 100%); border-bottom: 4px solid #f59e0b;">
        <div class="absolute inset-0 opacity-10" style="background-image:url('/images/programs/engineering.jpg');background-size:cover;background-position:center;"></div>
        <div class="relative max-w-7xl mx-auto px-6 text-center">
            <p class="text-xs font-extrabold tracking-widest uppercase mb-3 text-amber-300">Ministry of Sport, Recreation, Arts & Culture — Government of Zimbabwe</p>
            <h1 class="text-5xl font-bold text-white mb-4" style="font-family:'Georgia',serif;">Research & Innovation</h1>
            <div class="w-16 h-1 mx-auto rounded mb-6 bg-amber-400"></div>
            <p class="text-lg max-w-2xl mx-auto text-slate-100 font-medium">Advancing knowledge through cutting-edge research, international collaboration and applied innovation across engineering and technology disciplines.</p>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div style="background:#0B120C;" class="border-b border-emerald-900/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-emerald-800/40">
                <div class="py-6 text-center"><p class="text-2xl font-bold text-amber-400">200+</p><p class="text-xs mt-0.5 text-slate-300">Active Researchers</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">50+</p><p class="text-xs mt-0.5 text-slate-300">Research Labs</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-amber-400">30+</p><p class="text-xs mt-0.5 text-slate-300">International Partners</p></div>
                <div class="py-6 text-center"><p class="text-2xl font-bold text-white">500+</p><p class="text-xs mt-0.5 text-slate-300">Publications / Year</p></div>
            </div>
        </div>
    </div>

    {{-- Research Overview --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs font-extrabold tracking-widest uppercase mb-3 text-amber-600">Our Approach</p>
                    <h2 class="text-4xl font-bold mb-6 text-[#005A2B]" style="font-family:'Georgia',serif;">Research That Makes a Difference</h2>
                    <div class="w-16 h-1 rounded mb-8 bg-[#005A2B]"></div>
                    <p class="text-gray-600 leading-relaxed mb-5">At MoSRAC, research is not confined to laboratories — it is embedded in every aspect of our academic culture. Our faculty and students collaborate with industry partners, government agencies and international universities to tackle real-world engineering and technology challenges.</p>
                    <p class="text-gray-600 leading-relaxed mb-5">From AI and machine learning to sustainable energy systems, structural engineering and advanced materials, our research programs address the most pressing challenges facing the region and global community.</p>
                    <p class="text-gray-600 leading-relaxed">Our graduates leave not just as practitioners, but as innovators — equipped with the mindset and tools to contribute to research throughout their careers.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden shadow-md h-48"><img src="/images/programs/engineering.jpg" alt="Engineering Research" class="w-full h-full object-cover"></div>
                    <div class="rounded-2xl overflow-hidden shadow-md h-48 mt-8"><img src="/images/programs/it.jpg" alt="IT Research" class="w-full h-full object-cover"></div>
                    <div class="rounded-2xl overflow-hidden shadow-md h-48"><img src="/images/programs/architecture.jpg" alt="Architecture Research" class="w-full h-full object-cover"></div>
                    <div class="rounded-2xl overflow-hidden shadow-md h-48 mt-8" style="background:linear-gradient(135deg,#005A2B,#00421F);">
                        <div class="w-full h-full flex flex-col items-center justify-center text-white p-4 text-center">
                            <p class="text-3xl font-bold text-amber-300" style="font-family:'Georgia',serif;">50+</p>
                            <p class="text-sm mt-1 text-slate-100 font-medium">State-of-the-art Labs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Research Areas --}}
    <section class="py-20 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-xs font-extrabold tracking-widest uppercase mb-3 text-amber-600">Focus Areas</p>
                <h2 class="text-4xl font-bold text-[#005A2B]" style="font-family:'Georgia',serif;">Research Disciplines</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded bg-[#005A2B]"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['🤖','Artificial Intelligence & Machine Learning','Deep learning, NLP, computer vision and intelligent systems applied to engineering problems.','#ecfdf5','#005A2B'],
                    ['⚡','Sustainable Energy Systems','Renewable energy, smart grids, power electronics and energy efficiency research.','#fef3c7','#b45309'],
                    ['🏗️','Structural & Civil Engineering','Advanced materials, seismic resilience, structural analysis and smart infrastructure.','#f0fdf4','#16a34a'],
                    ['💻','Software Engineering & Cybersecurity','Secure systems, distributed computing, blockchain and software architecture.','#f5f3ff','#7c3aed'],
                    ['🧪','Materials Science & Nanotechnology','Novel materials, nano-engineering and advanced manufacturing processes.','#fdf2f8','#9d174d'],
                    ['📡','Telecommunications & Signal Processing','5G systems, satellite communications, signal processing and wireless networks.','#ecfdf5','#005A2B'],
                ] as $area)
                    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300 group">
                        <div class="text-4xl mb-4">{{ $area[0] }}</div>
                        <h3 class="font-bold text-lg mb-3 text-[#005A2B]" style="font-family:'Georgia',serif;">{{ $area[1] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $area[2] }}</p>
                        <div class="mt-5 h-0.5 w-8 group-hover:w-16 transition-all duration-300 rounded bg-[#005A2B]"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Research Labs --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-xs font-extrabold tracking-widest uppercase mb-3 text-amber-600">Infrastructure</p>
                <h2 class="text-4xl font-bold text-[#005A2B]" style="font-family:'Georgia',serif;">Research Laboratories</h2>
                <div class="w-16 h-1 mx-auto mt-4 rounded bg-[#005A2B]"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto text-sm">Our 50+ specialised laboratories provide researchers and students with world-class equipment and infrastructure to conduct cutting-edge experiments and applied research.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['AI & Robotics Lab','Advanced robotics, computer vision and autonomous systems research facility.'],
                    ['Cybersecurity Lab','Dedicated environment for network security, penetration testing and secure systems development.'],
                    ['Materials Testing Lab','State-of-the-art equipment for structural analysis, material strength and durability testing.'],
                    ['Renewable Energy Lab','Solar, wind and battery systems research with full-scale prototyping capabilities.'],
                    ['Electronics Lab','Circuit design, embedded systems and PCB prototyping for hardware engineering research.'],
                    ['3D Printing & Fabrication','Additive manufacturing lab for rapid prototyping across engineering disciplines.'],
                    ['Data Science Centre','High-performance computing cluster for big data analysis and machine learning experiments.'],
                    ['Environmental Engineering Lab','Water quality, air pollution and environmental systems analysis laboratory.'],
                ] as $lab)
                    <div class="border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-sm transition-all duration-300">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-4 bg-emerald-50">
                            <svg class="w-4 h-4 text-[#005A2B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h4 class="font-bold text-sm mb-2 text-[#005A2B]">{{ $lab[0] }}</h4>
                        <p class="text-gray-500 text-xs leading-relaxed">{{ $lab[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- International Collaboration --}}
    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs font-extrabold tracking-widest uppercase mb-3 text-amber-600">Global Reach</p>
                    <h2 class="text-4xl font-bold mb-6 text-[#005A2B]" style="font-family:'Georgia',serif;">International Research Collaboration</h2>
                    <div class="w-16 h-1 rounded mb-8 bg-[#005A2B]"></div>
                    <p class="text-gray-600 leading-relaxed mb-5">MoSRAC maintains active research partnerships with universities and research institutes across Europe, North America and Asia. Our researchers participate in joint projects, international conferences and student exchange programs that bring global perspectives to local engineering.</p>
                    <p class="text-gray-600 leading-relaxed mb-8">These collaborations result in co-authored publications, shared patents and joint research grants that strengthen both MoSRAC's profile and the quality of research conducted on campus.</p>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach([['30+','Partner Universities'],['15+','Countries'],['50+','Joint Projects']] as $s)
                            <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-slate-200">
                                <p class="text-xl font-bold text-[#005A2B]" style="font-family:'Georgia',serif;">{{ $s[0] }}</p>
                                <p class="text-xs text-gray-500 mt-1 font-medium">{{ $s[1] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach([
                        ['European Research Council','Joint research grants in AI, materials science and sustainable engineering.'],
                        ['National Research Academy','Long-standing collaboration on fundamental and applied research programs.'],
                        ['Horizon Europe Program','Participation in EU-funded research initiatives and technology transfer projects.'],
                        ['Industry R&D Partners','Active research contracts with national and international technology companies.'],
                    ] as $partner)
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-emerald-50">
                                <svg class="w-5 h-5 text-[#005A2B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1 text-[#005A2B]">{{ $partner[0] }}</h4>
                                <p class="text-gray-500 text-xs leading-relaxed">{{ $partner[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="py-16" style="background: linear-gradient(135deg, #00421F 0%, #005A2B 100%);">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4" style="font-family:'Georgia',serif;">Join Our Research Community</h2>
            <p class="mb-8 text-slate-100">Pursue postgraduate research at MoSRAC and contribute to innovation that shapes the future.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('application.selectType') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-extrabold transition-all hover:shadow-lg bg-amber-400 text-emerald-950 hover:bg-amber-300">
                    Apply for Research <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-extrabold border border-white/40 text-white hover:bg-white/10 transition-all">
                    About MoSRAC
                </a>
            </div>
        </div>
    </div>

@endsection
