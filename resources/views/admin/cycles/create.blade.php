@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">
                    <div class="max-w-2xl mx-auto">

                        <div class="flex items-center gap-4 mb-8">
                            <a href="{{ route('admin.cycles.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                            <h1 class="text-2xl font-bold text-blue-900">New Admission Cycle</h1>
                        </div>

                        <form method="POST" action="{{ route('admin.cycles.store') }}"
                              class="bg-white rounded-xl shadow p-8 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Program *</label>
                                <select name="program_id" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                        onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                        onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                    <option value="">Select a program</option>
                                    @foreach($programs->groupBy('degree_level') as $level => $group)
                                        <optgroup label="{{ ucfirst($level) }}">
                                            @foreach($group as $program)
                                                <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>
                                                    {{ $program->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('program_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Intake Name *</label>
                                <input type="text" name="intake_name" value="{{ old('intake_name') }}"
                                       placeholder="e.g. Fall 2026, Spring 2027"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('intake_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Opens On *</label>
                                    <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                                           required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                    @error('starts_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Deadline *</label>
                                    <input type="date" name="deadline_at" value="{{ old('deadline_at') }}"
                                           required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                    @error('deadline_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">
                                    Capacity <span class="text-gray-400 font-normal">(optional — leave blank for unlimited)</span>
                                </label>
                                <input type="number" name="capacity" value="{{ old('capacity') }}"
                                       min="1" placeholder="e.g. 50"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.cycles.index') }}"
                                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                    Create Cycle
                                </button>
                            </div>
                        </form>
                    </div>

                </div>{{-- end centered form --}}
            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
