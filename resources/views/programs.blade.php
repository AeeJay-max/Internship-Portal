@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <div class="py-20" style="background: linear-gradient(135deg, #011627 0%, #011C3E 100%);">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: #90caf9;">
                National Internship Portal of Armenia
            </p>
            <h1 class="text-5xl font-bold text-white mb-4" style="font-family: 'Georgia', serif;">
                Academic Programs
            </h1>
            <p class="text-lg max-w-2xl mx-auto mb-8" style="color: rgba(255,255,255,0.75);">
                Explore our full range of Bachelor's, Master's, and PhD programs designed to prepare you for global careers in engineering, technology, and sciences.
            </p>

            {{-- Search bar --}}
            <div class="max-w-lg mx-auto relative mb-8">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="program-search"
                       placeholder="Search programs or faculty..."
                       class="w-full pl-11 pr-4 py-3 rounded-xl text-sm bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:bg-white/20 focus:border-white/40 transition-all"
                       oninput="filterPrograms()">
            </div>

            {{-- Level filter tabs --}}
            <div class="flex items-center justify-center gap-3 flex-wrap">
                @foreach(['all' => 'All Programs', 'bachelor' => "Bachelor's", 'master' => "Master's", 'phd' => 'PhD'] as $val => $label)
                    <button onclick="setLevel('{{ $val }}')"
                            id="tab-{{ $val }}"
                            class="level-tab px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200
                                   {{ $val === 'all' ? 'bg-white text-MOSRAC-navy' : 'text-white border border-white/30 hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div style="background: #011C3E;">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-3 divide-x divide-white/20">
                <div class="py-5 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['total_programs'] }}</p>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.65);">Academic Programs</p>
                </div>
                <div class="py-5 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['years_excellence'] }}+</p>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.65);">Years of Excellence</p>
                </div>
                <div class="py-5 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['open_cycles'] }}</p>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.65);">Open Intakes</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Programs Grid --}}
    <div class="py-16" style="background: #f0f4f8;">
        <div class="max-w-7xl mx-auto px-6">

            @php
                $levelConfig = [
                    'bachelor' => ['label' => "Bachelor's Programs", 'sublabel' => '4-year undergraduate degrees',  'duration' => '4 Years',   'badge_bg' => '#eff6ff', 'badge_color' => '#1d4ed8', 'badge' => 'BSc', 'icon_bg' => '#dbeafe', 'icon_color' => '#1e40af'],
                    'master'   => ['label' => "Master's Programs",   'sublabel' => '2-year postgraduate degrees',  'duration' => '2 Years',   'badge_bg' => '#f5f3ff', 'badge_color' => '#7c3aed', 'badge' => 'MSc', 'icon_bg' => '#ede9fe', 'icon_color' => '#6d28d9'],
                    'phd'      => ['label' => 'PhD Programs',        'sublabel' => 'Doctoral research degrees',    'duration' => '3–5 Years', 'badge_bg' => '#fef3c7', 'badge_color' => '#b45309', 'badge' => 'PhD', 'icon_bg' => '#fde68a', 'icon_color' => '#92400e'],
                ];

                // Flatten all programs for JS-driven filtering
                $allPrograms = collect();
                foreach (['bachelor', 'master', 'phd'] as $level) {
                    if (isset($programs[$level])) {
                        $allPrograms = $allPrograms->merge($programs[$level]);
                    }
                }
            @endphp

            {{-- No results message --}}
            <div id="no-results" class="hidden text-center py-20">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-gray-400 text-lg font-medium">No programs match your search.</p>
                <button onclick="clearFilters()" class="mt-3 text-sm font-semibold" style="color:#611818;">Clear filters</button>
            </div>

            {{-- Level sections --}}
            @foreach(['bachelor', 'master', 'phd'] as $level)
                @if(isset($programs[$level]) && $programs[$level]->isNotEmpty())
                    @php $cfg = $levelConfig[$level]; @endphp

                    <div id="section-{{ $level }}" class="level-section mb-16 scroll-mt-8" data-level="{{ $level }}">

                        {{-- Section header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: {{ $cfg['icon_bg'] }};">
                                @if($level === 'bachelor')
                                    <svg class="w-6 h-6" fill="none" stroke="{{ $cfg['icon_color'] }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                @elseif($level === 'master')
                                    <svg class="w-6 h-6" fill="none" stroke="{{ $cfg['icon_color'] }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="{{ $cfg['icon_color'] }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold" style="color:#011C3E; font-family:'Georgia',serif;">{{ $cfg['label'] }}</h2>
                                <p class="text-sm text-gray-500">{{ $cfg['sublabel'] }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="text-sm font-semibold px-3 py-1 rounded-full" style="background:{{ $cfg['badge_bg'] }}; color:{{ $cfg['badge_color'] }}">
                                    {{ $programs[$level]->count() }} {{ Str::plural('program', $programs[$level]->count()) }}
                                </span>
                            </div>
                        </div>

                        {{-- Cards grid --}}
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="grid-{{ $level }}">
                            @foreach($programs[$level] as $program)
                                @php
                                    $openCycles   = $program->admissionCycles->filter(fn($c) => now()->between($c->starts_at, $c->deadline_at));
                                    $nextDeadline = $openCycles->sortBy('deadline_at')->first();
                                    $imgMap = ['engineering' => '/images/programs/engineering.jpg', 'computer' => '/images/programs/it.jpg', 'information' => '/images/programs/it.jpg', 'architecture' => '/images/programs/architecture.jpg', 'cyber' => '/images/programs/it.jpg', 'management' => '/images/programs/engineering.jpg', 'science' => '/images/programs/it.jpg'];
                                    $img = $program->image_path ? asset('storage/'.$program->image_path) : ($imgMap[collect(array_keys($imgMap))->first(fn($k) => str_contains(strtolower($program->name), $k))] ?? null);

                                    // Build full details for modal
                                    $details = [
                                        'id'          => $program->id,
                                        'name'        => $program->name,
                                        'faculty'     => $program->faculty ?? '',
                                        'level'       => $cfg['label'],
                                        'badge'       => $cfg['badge'],
                                        'duration'    => $cfg['duration'],
                                        'description' => $program->description ?: 'A comprehensive program designed to equip students with advanced knowledge and practical skills for careers in their field.',
                                        'isOpen'      => $openCycles->isNotEmpty(),
                                        'deadline'    => $nextDeadline ? \Carbon\Carbon::parse($nextDeadline->deadline_at)->format('M d, Y') : null,
                                        'intake'      => $nextDeadline->intake_name ?? null,
                                        'badge_bg'    => $cfg['badge_bg'],
                                        'badge_color' => $cfg['badge_color'],
                                    ];
                                @endphp

                                <div class="program-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-gray-100 flex flex-col"
                                     data-level="{{ $level }}"
                                     data-name="{{ strtolower($program->name) }}"
                                     data-faculty="{{ strtolower($program->faculty ?? '') }}">

                                    {{-- Image --}}
                                    <div class="relative h-44 overflow-hidden cursor-pointer" onclick='openModal({{ json_encode($details) }})'>
                                        @if($img)
                                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-110" style="background-image:url('{{ $img }}');"></div>
                                            <div class="absolute inset-0" style="background:rgba(2,62,138,0.35);"></div>
                                        @else
                                            <div class="absolute inset-0" style="background:linear-gradient(135deg,#011C3E 0%,#611818 100%);"></div>
                                        @endif
                                        <div class="absolute top-3 left-3">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full" style="background:{{ $cfg['badge_bg'] }};color:{{ $cfg['badge_color'] }}">{{ $cfg['badge'] }}</span>
                                        </div>
                                        @if($openCycles->isNotEmpty())
                                            <div class="absolute top-3 right-3">
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-500 text-white flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>Open
                                                </span>
                                            </div>
                                        @endif
                                        <div class="absolute bottom-3 right-3">
                                            <span class="text-xs font-medium px-2 py-1 rounded bg-black/40 text-white">{{ $cfg['duration'] }}</span>
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-6 flex flex-col flex-1">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold mb-1" style="color:#011C3E;font-family:'Georgia',serif;">{{ $program->name }}</h3>
                                            @if($program->faculty)
                                                <p class="text-xs font-medium mb-3" style="color:#611818;">{{ $program->faculty }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                                                {{ $program->description ?: 'A comprehensive program designed to equip students with advanced knowledge and practical skills for careers in their field.' }}
                                            </p>
                                        </div>

                                        @if($nextDeadline)
                                            <div class="mt-4 flex items-center gap-2 text-xs text-orange-600 font-medium bg-orange-50 px-3 py-2 rounded-lg">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Deadline: {{ \Carbon\Carbon::parse($nextDeadline->deadline_at)->format('M d, Y') }}
                                            </div>
                                        @endif

                                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                                            <button onclick='openModal({{ json_encode($details) }})'
                                                    class="inline-flex items-center gap-1.5 text-sm font-semibold transition-colors duration-200" style="color:#611818;">
                                                Learn More
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                            </button>
                                            @if($openCycles->isNotEmpty())
                                                <a href="{{ route('apply.start') }}"
                                                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-white px-4 py-2 rounded-lg transition-all hover:shadow-md"
                                                   style="background:#011C3E;">
                                                    Apply Now
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium">Coming soon</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif
            @endforeach

            @if($programs->isEmpty())
                <div class="text-center py-20">
                    <p class="text-gray-400 text-lg">No programs available at this time.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- Program Detail Modal --}}
    <div id="program-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background:rgba(1,22,39,0.75); backdrop-filter:blur(4px);"
         onclick="if(event.target===this) closeModal()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">

            {{-- Modal image header --}}
            <div class="relative h-52 overflow-hidden rounded-t-2xl" id="modal-image-area">
                <div id="modal-img" class="absolute inset-0 bg-cover bg-center" style="background:linear-gradient(135deg,#011C3E 0%,#611818 100%);"></div>
                <div class="absolute inset-0" style="background:rgba(1,28,62,0.45);"></div>
                <button onclick="closeModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="absolute bottom-4 left-6 flex items-center gap-3">
                    <span id="modal-badge" class="text-xs font-bold px-3 py-1 rounded-full">BSc</span>
                    <span id="modal-open-badge" class="hidden text-xs font-semibold px-2.5 py-1 rounded-full bg-green-500 text-white flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>Applications Open
                    </span>
                </div>
            </div>

            {{-- Modal content --}}
            <div class="p-8">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h2 id="modal-name" class="text-2xl font-bold" style="color:#011C3E;font-family:'Georgia',serif;"></h2>
                        <p id="modal-faculty" class="text-sm font-medium mt-1" style="color:#611818;"></p>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0 ml-4">
                        <span id="modal-level" class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600"></span>
                        <span id="modal-duration" class="text-xs text-gray-400"></span>
                    </div>
                </div>

                <hr class="my-5 border-gray-100">

                {{-- About --}}
                <div class="mb-6">
                    <h4 class="text-sm font-bold uppercase tracking-wide text-gray-400 mb-3">About This Program</h4>
                    <p id="modal-description" class="text-gray-600 leading-relaxed text-sm"></p>
                </div>

                {{-- Key info grid --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Duration</p>
                        <p id="modal-duration-2" class="font-semibold text-sm" style="color:#011C3E;"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Degree</p>
                        <p id="modal-level-2" class="font-semibold text-sm" style="color:#011C3E;"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Faculty</p>
                        <p id="modal-faculty-2" class="font-semibold text-sm" style="color:#011C3E;"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4" id="modal-deadline-box">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Application Deadline</p>
                        <p id="modal-deadline" class="font-semibold text-sm text-orange-600"></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <a href="{{ route('apply.start') }}" id="modal-apply-btn"
                       class="flex-1 inline-flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold text-white transition-all hover:shadow-lg"
                       style="background:#011C3E;">
                        Apply Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <button onclick="closeModal()"
                            class="px-6 py-3 rounded-xl text-sm font-semibold border-2 transition-all"
                            style="border-color:#011C3E;color:#011C3E;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="py-16" style="background:#011C3E;">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4" style="font-family:'Georgia',serif;">Ready to Apply?</h2>
            <p class="mb-2" style="color:rgba(255,255,255,0.75);">Join students who chose MoSRAC for their academic journey.</p>
            <p class="text-sm mb-8" style="color:rgba(255,255,255,0.5);">Applications are reviewed on a rolling basis. Apply early to secure your place.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('apply.start') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold transition-all hover:shadow-lg hover:-translate-y-0.5" style="background:white;color:#011C3E;">
                    Start Your Application
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-lg text-sm font-bold border border-white/30 text-white hover:bg-white/10 transition-all">
                    Learn More About MoSRAC
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let activeLevel = 'all';

        function setLevel(level) {
            activeLevel = level;

            // Update tab styles
            document.querySelectorAll('.level-tab').forEach(t => {
                t.style.background = 'transparent';
                t.style.color = 'white';
                t.classList.remove('bg-white');
            });
            const active = document.getElementById('tab-' + level);
            if (active) {
                active.style.background = 'white';
                active.style.color = '#011C3E';
            }

            filterPrograms();

            // Scroll to section if specific level
            if (level !== 'all') {
                const el = document.getElementById('section-' + level);
                if (el) setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
            }
        }

        function filterPrograms() {
            const query = document.getElementById('program-search').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.program-card');
            const sections = document.querySelectorAll('.level-section');
            let totalVisible = 0;

            sections.forEach(section => {
                const level = section.dataset.level;
                const sectionCards = section.querySelectorAll('.program-card');
                let sectionVisible = 0;

                sectionCards.forEach(card => {
                    const matchLevel = activeLevel === 'all' || card.dataset.level === activeLevel;
                    const matchSearch = !query || card.dataset.name.includes(query) || card.dataset.faculty.includes(query);

                    if (matchLevel && matchSearch) {
                        card.style.display = '';
                        sectionVisible++;
                        totalVisible++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                section.style.display = sectionVisible > 0 ? '' : 'none';
            });

            document.getElementById('no-results').classList.toggle('hidden', totalVisible > 0);
        }

        function clearFilters() {
            document.getElementById('program-search').value = '';
            setLevel('all');
        }

        // Modal
        function openModal(data) {
            const modal = document.getElementById('program-modal');

            document.getElementById('modal-name').textContent        = data.name;
            document.getElementById('modal-faculty').textContent     = data.faculty;
            document.getElementById('modal-faculty-2').textContent   = data.faculty || '—';
            document.getElementById('modal-level').textContent       = data.level;
            document.getElementById('modal-level-2').textContent     = data.level;
            document.getElementById('modal-duration').textContent    = data.duration;
            document.getElementById('modal-duration-2').textContent  = data.duration;
            document.getElementById('modal-description').textContent = data.description;

            const badge = document.getElementById('modal-badge');
            badge.textContent        = data.badge;
            badge.style.background   = data.badge_bg;
            badge.style.color        = data.badge_color;

            const openBadge = document.getElementById('modal-open-badge');
            openBadge.classList.toggle('hidden', !data.isOpen);
            openBadge.classList.toggle('flex', data.isOpen);

            const deadlineBox = document.getElementById('modal-deadline-box');
            if (data.deadline) {
                deadlineBox.style.display = '';
                document.getElementById('modal-deadline').textContent = data.deadline;
            } else {
                deadlineBox.style.display = 'none';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('program-modal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>
@endpush
