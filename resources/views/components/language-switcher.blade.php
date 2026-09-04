{{--
  Language Switcher Component — Globe dropdown
  Shows a globe icon + current language code.
  On click opens a dropdown with EN, RU, ՀԱՅ options.
  Uses Alpine.js (already loaded in the project).
--}}

@php
    $current = app()->getLocale();
    $languages = [
        'en' => ['label' => 'EN',  'full' => 'English'],
        'ru' => ['label' => 'RU',  'full' => 'Русский'],
        'hy' => ['label' => 'ՀԱՅ', 'full' => 'Հայերեն'],
    ];
    $currentLabel = $languages[$current]['label'] ?? 'EN';
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">

    {{-- Trigger button --}}
    <button @click="open = !open"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-150 select-none"
            style="color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.08);"
            onmouseover="this.style.background='rgba(255,255,255,0.15)'"
            onmouseout="if(!document.querySelector('[x-data]').__x.$data.open) this.style.background='rgba(255,255,255,0.08)'">

        {{-- Globe SVG --}}
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <path d="M2 12h20"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>

        {{-- Current language code --}}
        <span>{{ $currentLabel }}</span>

        {{-- Chevron --}}
        <svg class="w-3 h-3 opacity-60 transition-transform duration-200"
             :class="open ? 'rotate-180' : ''"
             fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
         class="absolute right-0 mt-2 w-40 rounded-xl shadow-xl overflow-hidden z-50"
         style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08); top: 100%;">

        @foreach($languages as $code => $lang)
            <a href="{{ route('lang.switch', $code) }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors duration-100
                      {{ $current === $code ? 'font-bold' : 'font-medium' }}"
               style="{{ $current === $code
                    ? 'background: #f0f4ff; color: #011C3E;'
                    : 'color: #374151;' }}"
               onmouseover="if('{{ $current }}' !== '{{ $code }}') this.style.background='#f9fafb'"
               onmouseout="if('{{ $current }}' !== '{{ $code }}') this.style.background='transparent'">

                {{-- Active indicator dot --}}
                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                      style="{{ $current === $code ? 'background: #011C3E;' : 'background: transparent;' }}">
                </span>

                {{-- Language code --}}
                <span class="tracking-wide">{{ $lang['label'] }}</span>

                {{-- Full name --}}
                <span class="ml-auto text-xs opacity-50">{{ $lang['full'] }}</span>
            </a>
        @endforeach

    </div>
</div>
