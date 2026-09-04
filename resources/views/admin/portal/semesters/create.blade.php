@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">
                    <div class="max-w-lg">
                        <a href="{{ route('admin.portal.semesters.index') }}" class="text-blue-600 hover:underline text-sm">← Semesters</a>
                        <h1 class="text-2xl font-bold text-blue-900 mt-2 mb-6">
                            {{ isset($semester) ? 'Edit Semester' : 'New Semester' }}
                        </h1>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ isset($semester) ? route('admin.portal.semesters.update', $semester) : route('admin.portal.semesters.store') }}"
                              class="bg-white rounded-2xl shadow border border-gray-100 p-6 space-y-5">
                            @csrf
                            @if(isset($semester)) @method('PUT') @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Semester Name *</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $semester->name ?? '') }}"
                                       placeholder="e.g. Spring 2026"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year *</label>
                                <input type="text" name="academic_year"
                                       value="{{ old('academic_year', $semester->academic_year ?? '') }}"
                                       placeholder="e.g. 2025-2026"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                    <input type="date" name="starts_at"
                                           value="{{ old('starts_at', isset($semester) ? $semester->starts_at->format('Y-m-d') : '') }}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                    <input type="date" name="ends_at"
                                           value="{{ old('ends_at', isset($semester) ? $semester->ends_at->format('Y-m-d') : '') }}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit"
                                        class="bg-blue-900 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-800 transition">
                                    {{ isset($semester) ? 'Save Changes' : 'Create Semester' }}
                                </button>
                                <a href="{{ route('admin.portal.semesters.index') }}"
                                   class="px-6 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
