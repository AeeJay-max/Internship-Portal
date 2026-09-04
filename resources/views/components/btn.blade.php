@props([
    'variant' => 'primary',
    'size'    => 'md',
    'href'    => null,
    'type'    => 'button',
])

@php
    $variants = [
        'primary'         => 'btn-primary',
        'crimson'         => 'btn-crimson',
        'outline'         => 'btn-outline',
        'outline-crimson' => 'btn-outline-crimson',
        'ghost'           => 'btn-ghost',
        'white'           => 'btn-white',
    ];
    $sizes = [
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
    ];
    $classes = trim(($variants[$variant] ?? 'btn-primary') . ' ' . ($sizes[$size] ?? ''));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
