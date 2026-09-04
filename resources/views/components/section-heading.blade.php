@props([
    'label'    => null,
    'title'    => '',
    'subtitle' => null,
    'center'   => false,
])

<div class="MOSRAC-section-heading {{ $center ? 'text-center' : '' }}">
    @if($label)
        <span class="label-section block mb-3">{{ $label }}</span>
    @endif

    <h2>{{ $title }}</h2>

    <div class="MOSRAC-section-divider {{ $center ? 'mx-auto' : '' }}"></div>

    @if($subtitle)
        <p class="mt-4 text-lg max-w-2xl {{ $center ? 'mx-auto' : '' }}">{{ $subtitle }}</p>
    @endif
</div>
