@extends('application.layout')

@section('step-content')
<form method="POST" action="{{ route('application.personal.store') }}" class="space-y-6">
    @csrf

    <div class="border-b border-slate-200 pb-4 mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Step 1 — Personal Information</h2>
        <p class="text-xs text-slate-500">Provide your official contact and identification details for Ministry processing.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name', $personal->first_name ?? Auth::user()->name) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $personal->middle_name ?? '') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $personal->last_name ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="date_of_birth" class="block text-xs font-bold text-slate-700 uppercase mb-1">Date of Birth *</label>
            @php
                $maxDob = now()->subYears(16)->format('Y-m-d');
            @endphp
            <input type="date" 
                   id="date_of_birth" 
                   name="date_of_birth" 
                   max="{{ $maxDob }}"
                   value="{{ old('date_of_birth', optional($personal->date_of_birth ?? null)->format('Y-m-d')) }}" 
                   required 
                   class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
            <p id="dob_error" class="hidden text-xs text-rose-600 font-semibold mt-1.5 flex items-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>Applicants must be at least 16 years of age to apply.</span>
            </p>
            <p class="text-[11px] text-slate-500 mt-1">Must be at least 16 years of age.</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gender *</label>
            <select name="gender" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender', $personal->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $personal->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nationality *</label>
            <input type="text" name="nationality" value="{{ old('nationality', $personal->nationality ?? 'Zimbabwean') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">National ID Number *</label>
            <input type="text" name="national_id" value="{{ old('national_id', $personal->national_id ?? '') }}" required placeholder="e.g. 63-1234567-A-63" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Passport Number (Optional)</label>
            <input type="text" name="passport_number" value="{{ old('passport_number', $personal->passport_number ?? '') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number *</label>
            <input type="text" name="phone" value="{{ old('phone', $personal->phone ?? Auth::user()->phone) }}" required placeholder="+263 77 123 4567" class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Town *</label>
            <input type="text" name="city" value="{{ old('city', $personal->city ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
        </div>

        <div>
            <label for="province" class="block text-xs font-bold text-slate-700 uppercase mb-1">Province *</label>
            @php
                $provinces = [
                    'Bulawayo',
                    'Harare',
                    'Manicaland',
                    'Mashonaland Central',
                    'Mashonaland East',
                    'Mashonaland West',
                    'Masvingo',
                    'Matabeleland North',
                    'Matabeleland South',
                    'Midlands',
                ];
                $selectedProvince = old('province', $personal->province ?? '');
            @endphp
            <select id="province" 
                    name="province" 
                    required 
                    class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
                <option value="">-- Select Province --</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov }}" {{ $selectedProvince === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Residential Address *</label>
        <textarea name="address" rows="2" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">{{ old('address', $personal->address ?? '') }}</textarea>
    </div>

    <div class="pt-4 border-t border-slate-200">
        <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Emergency Contact Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Contact Name *</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $personal->emergency_contact_name ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Contact Phone *</label>
                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $personal->emergency_contact_phone ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-[#005A2B] focus:border-[#005A2B]">
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            Cancel & Save Draft
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-[#005A2B] text-white font-bold text-sm uppercase tracking-wider hover:bg-[#00421F] transition shadow">
            Save & Continue to Academic Background &rarr;
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dobInput = document.getElementById('date_of_birth');
        const dobError = document.getElementById('dob_error');
        const form = dobInput ? dobInput.closest('form') : null;

        if (dobInput) {
            function validateAge() {
                if (!dobInput.value) {
                    dobInput.setCustomValidity('');
                    if (dobError) dobError.classList.add('hidden');
                    dobInput.classList.remove('border-rose-500', 'focus:ring-rose-500', 'focus:border-rose-500');
                    return true;
                }

                const parts = dobInput.value.split('-');
                if (parts.length !== 3) return true;

                const birthYear = parseInt(parts[0], 10);
                const birthMonth = parseInt(parts[1], 10) - 1;
                const birthDay = parseInt(parts[2], 10);
                const birthDate = new Date(birthYear, birthMonth, birthDay);

                const today = new Date();
                let age = today.getFullYear() - birthYear;
                const m = today.getMonth() - birthMonth;
                if (m < 0 || (m === 0 && today.getDate() < birthDay)) {
                    age--;
                }

                if (isNaN(birthDate.getTime()) || age < 16) {
                    dobInput.setCustomValidity('Applicants must be at least 16 years of age to apply.');
                    if (dobError) dobError.classList.remove('hidden');
                    dobInput.classList.add('border-rose-500', 'focus:ring-rose-500', 'focus:border-rose-500');
                    return false;
                } else {
                    dobInput.setCustomValidity('');
                    if (dobError) dobError.classList.add('hidden');
                    dobInput.classList.remove('border-rose-500', 'focus:ring-rose-500', 'focus:border-rose-500');
                    return true;
                }
            }

            dobInput.addEventListener('input', validateAge);
            dobInput.addEventListener('change', validateAge);

            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!validateAge()) {
                        e.preventDefault();
                        dobInput.reportValidity();
                        dobInput.focus();
                    }
                });
            }

            if (dobInput.value) {
                validateAge();
            }
        }
    });
</script>
@endpush
