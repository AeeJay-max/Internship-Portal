<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#005A2B] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#00421F] focus:bg-[#00421F] active:bg-[#002D15] focus:outline-none focus:ring-2 focus:ring-[#005A2B] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
