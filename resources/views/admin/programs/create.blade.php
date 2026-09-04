@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">
                    <div class="max-w-2xl mx-auto">

                        <div class="flex items-center gap-4 mb-8">
                            <a href="{{ route('admin.programs.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
                            <h1 class="text-2xl font-bold text-blue-900">New Program</h1>
                        </div>

                        <form method="POST" action="{{ route('admin.programs.store') }}"
                              enctype="multipart/form-data"
                              class="bg-white rounded-xl shadow p-8 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Program Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       placeholder="e.g. Cybersecurity Engineering"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Faculty / Department</label>
                                <input type="text" name="faculty" value="{{ old('faculty') }}"
                                       placeholder="e.g. Faculty of Computer Science & Engineering"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                @error('faculty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Degree Level *</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['bachelor' => "Bachelor's", 'master' => "Master's", 'phd' => 'PhD'] as $val => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="degree_level" value="{{ $val }}" class="sr-only peer"
                                                @checked(old('degree_level') === $val)>
                                            <div class="border-2 rounded-lg px-4 py-3 text-center text-sm font-semibold transition-all duration-200
                                peer-checked:text-white peer-checked:border-blue-800"
                                                 style="peer-checked:background:#011C3E;"
                                                 x-data
                                                 :class="$el.previousElementSibling.checked ? 'bg-blue-900 border-blue-900 text-white' : 'border-gray-200 text-gray-600 hover:border-blue-300'">
                                                {{ $label }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('degree_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Description</label>
                                <textarea name="description" rows="3"
                                          placeholder="Brief description of the program..."
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                          onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                          onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">{{ old('description') }}</textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 rounded text-blue-600" checked>
                                <label for="is_active" class="text-sm font-medium text-gray-700">Active — visible to applicants</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 rounded text-yellow-500">
                                <label for="is_featured" class="text-sm font-medium text-gray-700">★ Show in home page slider</label>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Slider Order <span class="text-gray-400 font-normal">(lower = first)</span></label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                       min="0" max="99"
                                       class="w-32 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                       onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                       onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Cover Photo</label>
                                <input type="file" name="image" accept="image/*"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm"
                                       onchange="showImgPreview(this, 'create-preview')">
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — max 8MB. Used as the program card background.</p>
                                <img id="create-preview" class="hidden mt-3 h-32 w-full object-cover rounded-lg border border-gray-200">
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.programs.index') }}"
                                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                        style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                    Create Program
                                </button>
                            </div>
                        </form>
                    </div>


                </div>{{-- end centered form --}}
            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
    @push('scripts')
        <script>
            function showImgPreview(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection
