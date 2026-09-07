<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    @foreach([
        ['update_password_current_password', 'current_password', 'Current Password', 'current-password'],
        ['update_password_password', 'password', 'New Password', 'new-password'],
        ['update_password_password_confirmation', 'password_confirmation', 'Confirm New Password', 'new-password'],
    ] as $field)
        <div>
            <label for="{{ $field[0] }}" class="block text-xs font-bold text-slate-800 mb-1.5">
                {{ $field[2] }}
            </label>
            <input id="{{ $field[0] }}" name="{{ $field[1] }}" type="password"
                   autocomplete="{{ $field[3] }}"
                   class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-xs bg-white transition focus:outline-none focus:border-[#005A2B] focus:ring-2 focus:ring-[#005A2B]/20"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->updatePassword->get('{{ $field[1] }}')" class="mt-1.5" />
        </div>
    @endforeach

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-6 py-3 font-bold text-xs uppercase tracking-wider text-white bg-[#005A2B] hover:bg-[#00421F] rounded-xl shadow-md transition-all">
            Update Password
        </button>

        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-xs font-bold text-[#005A2B]">
                ✓ Password updated
            </p>
        @endif
    </div>
</form>
