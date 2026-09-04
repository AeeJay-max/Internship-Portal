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
            <input type="text" name="first_name" value="{{ old('first_name', $personal->first_name ?? Auth::user()->name) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $personal->middle_name ?? '') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $personal->last_name ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date of Birth *</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($personal->date_of_birth ?? null)->format('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gender *</label>
            <select name="gender" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender', $personal->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $personal->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nationality *</label>
            <input type="text" name="nationality" value="{{ old('nationality', $personal->nationality ?? 'Zimbabwean') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">National ID Number *</label>
            <input type="text" name="national_id" value="{{ old('national_id', $personal->national_id ?? '') }}" required placeholder="e.g. 63-1234567-A-63" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Passport Number (Optional)</label>
            <input type="text" name="passport_number" value="{{ old('passport_number', $personal->passport_number ?? '') }}" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number *</label>
            <input type="text" name="phone" value="{{ old('phone', $personal->phone ?? Auth::user()->phone) }}" required placeholder="+263 77 123 4567" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Town *</label>
            <input type="text" name="city" value="{{ old('city', $personal->city ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Province *</label>
            <input type="text" name="province" value="{{ old('province', $personal->province ?? '') }}" required placeholder="e.g. Harare, Bulawayo, Manicaland" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Residential Address *</label>
        <textarea name="address" rows="2" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('address', $personal->address ?? '') }}</textarea>
    </div>

    <div class="pt-4 border-t border-slate-200">
        <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Emergency Contact Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Contact Name *</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $personal->emergency_contact_name ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Contact Phone *</label>
                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $personal->emergency_contact_phone ?? '') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-50">
            Cancel & Save Draft
        </a>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-900 text-white font-bold text-sm uppercase tracking-wider hover:bg-blue-800 transition shadow">
            Save & Continue to Academic Background &rarr;
        </button>
    </div>
</form>
@endsection
