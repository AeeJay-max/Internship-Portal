@php
    $application = $application ?? null;

    $steps = [
        ['label' => 'Personal',     'route' => 'application.personal',     'section' => 'personal'],
        ['label' => 'Academic',     'route' => 'application.academic',     'section' => 'academic'],
        ['label' => 'Preferences',  'route' => 'application.preferences',  'section' => 'preferences'],
        ['label' => 'Motivation',   'route' => 'application.motivation',   'section' => 'motivation'],
        ['label' => 'Documents',    'route' => 'application.documents',    'section' => 'documents'],
        ['label' => 'Review',       'route' => 'application.review',       'section' => null],
    ];

    $completedSections = $application ? $application->getCompletedSections() : [];

    $currentRoute = Route::currentRouteName();
    $currentIndex = 0;
    foreach ($steps as $i => $step) {
        if ($step['route'] === $currentRoute) {
            $currentIndex = $i;
            break;
        }
    }

    $totalSteps = count($steps);
@endphp

<div class="mb-10 px-4">
    <div class="relative flex items-center justify-between">
        <div class="absolute inset-x-0 h-0.5 bg-slate-200"
             style="top: 20px; left: calc(100% / {{ $totalSteps * 2 }}); right: calc(100% / {{ $totalSteps * 2 }});"></div>

        @foreach($steps as $index => $step)
            @php
                $isCompleted = $step['section'] && in_array($step['section'], $completedSections);
                $isCurrent   = $index === $currentIndex;
                $isPast      = $index < $currentIndex;

                if ($step['section'] === null) {
                    $isCompleted = $isPast;
                }

                $isDone = $isCompleted && !$isCurrent;
                $leftBlue  = $index <= $currentIndex;
                $rightBlue = $index < $currentIndex;
            @endphp

            <div class="relative flex flex-col items-center flex-1">
                @if($index > 0)
                    <div class="absolute h-0.5 right-1/2 left-0"
                         style="top: 20px; background-color: {{ $leftBlue ? '#011C3E' : '#e2e8f0' }};"></div>
                @endif

                @if($index < $totalSteps - 1)
                    <div class="absolute h-0.5 left-1/2 right-0"
                         style="top: 20px; background-color: {{ $rightBlue ? '#011C3E' : '#e2e8f0' }};"></div>
                @endif

                <a href="{{ route($step['route']) }}" class="relative z-10 flex flex-col items-center group">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full text-sm font-bold transition-all duration-200 shadow-sm
                        @if($isCurrent) bg-blue-950 text-white ring-4 ring-blue-200
                        @elseif($isDone) bg-blue-800 text-white
                        @else bg-white text-slate-400 border-2 border-slate-200
                        @endif">
                        @if($isDone)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>

                    <span class="hidden md:block mt-2 text-xs font-medium text-center leading-tight whitespace-nowrap
                        @if($isCurrent) text-blue-950 font-bold
                        @elseif($isDone) text-blue-700
                        @else text-slate-400
                        @endif">
                        {{ $step['label'] }}
                    </span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="md:hidden mt-4 text-center text-sm font-semibold text-blue-950">
        Step {{ $currentIndex + 1 }} of {{ $totalSteps }} — {{ $steps[$currentIndex]['label'] }}
    </div>

    @if($application)
        <div class="mt-6">
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div id="wizard-progress-bar"
                     class="h-2 rounded-full transition-all duration-500 bg-blue-950"
                     style="width: {{ $application->completion_percentage }}%;"></div>
            </div>
            <p class="text-xs text-slate-500 mt-1.5 text-right font-medium">
                <span id="wizard-progress-text">{{ $application->completion_percentage }}% complete</span>
            </p>
        </div>
    @endif
</div>
