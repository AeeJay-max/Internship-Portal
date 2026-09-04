<p class="text-sm text-gray-600 mb-6 leading-relaxed">
    Once your account is deleted, all data including applications and documents will be permanently removed. This action cannot be undone.
</p>

<button
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    class="px-6 py-2.5 font-semibold text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-200 hover:shadow-md">
    Delete My Account
</button>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
        @csrf
        @method('delete')

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Delete Account?</h2>
        </div>

        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
            This will permanently delete your account and all associated data. Please enter your password to confirm.
        </p>

        <div class="mb-6">
            <label for="del_password" class="block text-sm font-semibold mb-2" style="color: #011C3E;">Password</label>
            <input id="del_password" name="password" type="password"
                   class="w-full px-4 py-3 border rounded-lg text-sm bg-white focus:outline-none"
                   style="border-color: #d1dae6;"
                   onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                   onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';"
                   placeholder="Enter your password">
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
        </div>

        <div class="flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                Yes, Delete Account
            </button>
        </div>
    </form>
</x-modal>
