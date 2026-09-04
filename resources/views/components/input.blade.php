@props([
    'label'       => null,
    'name'        => '',
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'error'       => null,
    'icon'        => null,
])
<div>
    @if($label)
        <label for="{{ $name }}" class="MOSRAC-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </div>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'MOSRAC-input ' . ($icon ? 'pl-10 ' : '') . ($error ? 'has-error' : '')
            ]) }}
        >
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-red-600">{{ $error }}</p>
    @endif
</div>
