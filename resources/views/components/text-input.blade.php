@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#005A2B] focus:ring-[#005A2B] rounded-md shadow-sm']) }}>
