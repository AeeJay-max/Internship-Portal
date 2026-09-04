<form method="post" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    @foreach([
        ['update_password_current_password', 'current_password', 'Current Password', 'current-password'],
        ['update_password_password', 'password', 'New Password', 'new-password'],
        ['update_password_password_confirmation', 'password_confirmation', 'Confirm New Password', 'new-password'],
    ] as $field)
        <div>
            <label for="{{ $field[0] }}" class="block text-sm font-semibold mb-2" style="color: #011C3E;">
                {{ $field[2] }}
            </label>
            <input id="{{ $field[0] }}" name="{{ $field[1] }}" type="password"
                   autocomplete="{{ $field[3] }}"
                   class="w-full px-4 py-3 border rounded-lg text-sm bg-white transition-all duration-200 focus:outline-none"
                   style="border-color: #d1dae6;"
                   onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                   onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->updatePassword->get('{{ $field[1] }}')" class="mt-1.5" />
        </div>
    @endforeach

    <div class="flex items-center gap-4 pt-2">
        <button type="submit"
                class="px-7 py-2.5 font-semibold text-sm text-white rounded-lg transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
            Update Password
        </button>

        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-sm font-medium text-green-600">
                ✓ Password updated
            </p>
        @endif
    </div>
</form>
