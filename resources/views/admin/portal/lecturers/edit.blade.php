@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-4 md:px-6">
            <div class="flex gap-6">
                @include('admin.partials.sidebar')
                <div class="flex-1 min-w-0">
                    <div class="max-w-lg">
                        <a href="{{ route('admin.portal.lecturers.index') }}" class="text-blue-600 hover:underline text-sm">← Lecturers</a>
                        <h1 class="text-2xl font-bold text-blue-900 mt-2 mb-6">
                            {{ isset($lecturer) ? 'Edit Lecturer' : 'Add Lecturer' }}
                        </h1>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ isset($lecturer) ? route('admin.portal.lecturers.update', $lecturer) : route('admin.portal.lecturers.store') }}"
                              class="bg-white rounded-2xl shadow border border-gray-100 p-6 space-y-5">
                            @csrf
                            @if(isset($lecturer)) @method('PUT') @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $lecturer->name ?? '') }}"
                                       placeholder="e.g. Armen Simonyan"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <select name="title" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                                    @foreach(['Prof.', 'Assoc. Prof.', 'Dr.', 'Lecturer', 'Coach'] as $title)
                                        <option @selected(old('title', $lecturer->title ?? '') === $title)>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $lecturer->email ?? '') }}"
                                       placeholder="e.g. a.simonyan@MOSRAC.am"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                                <input type="text" name="faculty"
                                       value="{{ old('faculty', $lecturer->faculty ?? '') }}"
                                       placeholder="e.g. Faculty of Informatics & Applied Mathematics"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit"
                                        class="bg-blue-900 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-800 transition">
                                    {{ isset($lecturer) ? 'Save Changes' : 'Add Lecturer' }}
                                </button>
                                <a href="{{ route('admin.portal.lecturers.index') }}"
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
