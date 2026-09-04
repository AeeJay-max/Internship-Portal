@extends('application.layout')

@section('step-content')

    <h2 class="text-2xl font-semibold text-blue-900 mb-8">
        Transfer Information
    </h2>

    <form method="POST" action="{{ route('application.storeTransfer') }}">
        @csrf

        <div class="grid md:grid-cols-2 gap-8">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Previous Institution Name *
                </label>
                <input type="text"
                       name="previous_institution_name"
                       value="{{ old('previous_institution_name', $transfer->previous_institution_name ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">

                @error('previous_institution_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Institution Country *
                </label>
                <input type="text"
                       name="previous_institution_country"
                       value="{{ old('previous_institution_country', $transfer->previous_institution_country ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">

                @error('previous_institution_country')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Previous Degree Program *
                </label>
                <input type="text"
                       name="previous_degree_program"
                       value="{{ old('previous_degree_program', $transfer->previous_degree_program ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">

                @error('previous_degree_program')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Completed Semesters
                </label>
                <input type="number"
                       min="0"
                       name="completed_semesters"
                       value="{{ old('completed_semesters', $transfer->completed_semesters ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">

                @error('completed_semesters')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="mt-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Earned Credits
            </label>
            <input type="number"
                   min="0"
                   name="earned_credits"
                   value="{{ old('earned_credits', $transfer->earned_credits ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2">

            @error('earned_credits')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Reason for Transfer *
            </label>
            <textarea name="reason_for_transfer"
                      rows="4"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2">{{ old('reason_for_transfer', $transfer->reason_for_transfer ?? '') }}</textarea>

            @error('reason_for_transfer')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between mt-10">

            <a href="{{ route('application.academic') }}"
               class="px-6 py-3 rounded-lg border border-gray-300">
                Back
            </a>

            <button type="submit"
                    class="bg-blue-900 text-white px-6 py-3 rounded-lg hover:bg-blue-800">
                Save & Continue
            </button>

        </div>

    </form>

@endsection
