@extends('application.layout')

@section('step-content')

    <h2 class="text-2xl font-semibold text-blue-900 mb-8">
        Program Selection
    </h2>

    @php
        $cycle = $application->admissionCycle;
        $program = optional($cycle)->program;
    @endphp

    <div class="bg-gray-50 rounded-xl p-8 border border-gray-200">

        <div class="grid md:grid-cols-2 gap-8">

            {{-- Degree --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Degree Level
                </label>
                <div class="text-lg font-semibold text-blue-900 capitalize">
                    {{ $program->degree_level ?? '-' }}
                </div>
            </div>

            {{-- Program Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Program Name
                </label>
                <div class="text-lg font-semibold text-blue-900">
                    {{ $program->name ?? '-' }}
                </div>
            </div>

            {{-- Faculty --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Faculty
                </label>
                <div class="text-gray-700 text-lg">
                    {{ $program->faculty ?? '-' }}
                </div>
            </div>

            {{-- Intake --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Intake
                </label>
                <div class="text-gray-700 text-lg">
                    {{ $cycle->intake_name ?? '-' }}
                </div>
            </div>

            {{-- Application Window --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Application Window
                </label>
                <div class="text-gray-700">
                    {{ optional(optional($cycle)->starts_at)->format('M d, Y') ?? '-' }}
                    –
                    {{ optional(optional($cycle)->deadline_at)->format('M d, Y') ?? '-' }}
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    Cycle Status
                </label>
                <div class="text-gray-700 capitalize">
                    {{ $cycle->status ?? '-' }}
                </div>
            </div>

        </div>

    </div>

    <div class="mt-6 text-sm text-gray-500">
        If you wish to change your program selection, you must cancel this draft
        and start a new application.
    </div>

    <div class="flex justify-between mt-10">

        <a href="{{ route('application.academic') }}"
           class="px-6 py-3 border rounded-lg">
            Back
        </a>

        <form method="POST" action="{{ route('application.program.store') }}">
            @csrf
            <button type="submit"
                    class="bg-blue-900 text-white px-6 py-3 rounded-lg">
                Confirm & Continue
            </button>
        </form>

    </div>

@endsection
