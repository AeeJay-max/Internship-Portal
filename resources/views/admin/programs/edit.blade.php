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
                            <div>
                                <h1 class="text-2xl font-bold text-blue-900">
                                    {{ $isSuperAdmin ? 'Edit Program' : 'Update Program Content' }}
                                </h1>
                                @if(!$isSuperAdmin)
                                    <p class="text-xs text-gray-400 mt-0.5">You can update the cover photo and description only.</p>
                                @endif
                            </div>
                        </div>

                        @if($isSuperAdmin)
                            {{-- SUPER ADMIN: Full edit form --}}
                            <form method="POST" action="{{ route('admin.programs.update', $program) }}"
                                  enctype="multipart/form-data"
                                  class="bg-white rounded-xl shadow p-8 space-y-6">
                                @csrf @method('PUT')

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Program Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $program->name) }}"
                                           required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Faculty / Department</label>
                                    <input type="text" name="faculty" value="{{ old('faculty', $program->faculty) }}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Degree Level *</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['bachelor' => "Bachelor's", 'master' => "Master's", 'phd' => 'PhD'] as $val => $label)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="degree_level" value="{{ $val }}" class="sr-only"
                                                       id="level_{{ $val }}"
                                                    @checked(old('degree_level', $program->degree_level) === $val)>
                                                <div class="border-2 rounded-lg px-4 py-3 text-center text-sm font-semibold transition-all duration-200 cursor-pointer"
                                                     id="label_{{ $val }}"
                                                     onclick="selectLevel('{{ $val }}')">
                                                    {{ $label }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('degree_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Description</label>
                                    <textarea name="description" rows="4"
                                              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                              onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                              onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">{{ old('description', $program->description) }}</textarea>
                                </div>

                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                           class="w-4 h-4 rounded text-blue-600"
                                        @checked(old('is_active', $program->is_active))>
                                    <label for="is_active" class="text-sm font-medium text-gray-700">Active — visible to applicants</label>
                                </div>

                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                           class="w-4 h-4 rounded text-yellow-500"
                                        @checked(old('is_featured', $program->is_featured))>
                                    <label for="is_featured" class="text-sm font-medium text-gray-700">★ Show in home page slider</label>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Slider Order <span class="text-gray-400 font-normal">(lower = first)</span></label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order ?? 0) }}"
                                           min="0" max="99"
                                           class="w-32 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                           onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                           onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Cover Photo</label>
                                    @if($program->image_path)
                                        <div class="mb-3 relative">
                                            <img src="{{ asset('storage/' . $program->image_path) }}"
                                                 class="h-32 w-full object-cover rounded-lg border border-gray-200">
                                            <span class="absolute top-2 left-2 text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Current photo</span>
                                        </div>
                                    @endif
                                    <input type="file" name="image" accept="image/*"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm"
                                           onchange="showImgPreview(this, 'edit-preview')">
                                    <p class="text-xs text-gray-400 mt-1">Upload a new photo to replace the current one. JPG, PNG, WebP — max 8MB.</p>
                                    <img id="edit-preview" class="hidden mt-3 h-32 w-full object-cover rounded-lg border border-gray-200">
                                </div>

                                @if($program->admissionCycles->count() > 0)
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                                        ⚠️ This program has <strong>{{ $program->admissionCycles->count() }}</strong> admission cycle(s). Changes affect existing cycles.
                                    </div>
                                @endif

                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.programs.index') }}"
                                       class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                            style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                        Save Changes
                                    </button>
                                </div>
                            </form>

                        @else
                            {{-- ADMIN: Limited — image + description + featured only --}}
                            <form method="POST" action="{{ route('admin.programs.updateContent', $program) }}"
                                  enctype="multipart/form-data"
                                  class="bg-white rounded-xl shadow p-8 space-y-6">
                                @csrf

                                {{-- Read-only info --}}
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Program Info (read only)</p>
                                    <p class="font-semibold text-gray-800">{{ $program->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $program->faculty }} — {{ ucfirst($program->degree_level) }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Description</label>
                                    <textarea name="description" rows="5"
                                              placeholder="Write a clear description of this program for prospective students..."
                                              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                                              onfocus="this.style.borderColor='#611818'; this.style.boxShadow='0 0 0 3px rgba(0,119,182,0.1)';"
                                              onblur="this.style.borderColor='#d1dae6'; this.style.boxShadow='none';">{{ old('description', $program->description) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: #011C3E;">Cover Photo</label>
                                    @if($program->image_path)
                                        <div class="mb-3 relative">
                                            <img src="{{ asset('storage/' . $program->image_path) }}"
                                                 class="h-32 w-full object-cover rounded-lg border border-gray-200">
                                            <span class="absolute top-2 left-2 text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Current photo</span>
                                        </div>
                                    @endif
                                    <input type="file" name="image" accept="image/*"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm"
                                           onchange="showImgPreview(this, 'edit-preview')">
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — max 8MB. This appears as the program card background.</p>
                                    <img id="edit-preview" class="hidden mt-3 h-32 w-full object-cover rounded-lg border border-gray-200">
                                </div>

                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.programs.index') }}"
                                       class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                                            style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                        Save Content
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                    <script>
                        function selectLevel(val) {
                            ['bachelor','master','phd'].forEach(v => {
                                const label = document.getElementById('label_' + v);
                                const input = document.getElementById('level_' + v);
                                if (!label) return;
                                if (v === val) {
                                    input.checked = true;
                                    label.style.background = '#011C3E';
                                    label.style.borderColor = '#011C3E';
                                    label.style.color = 'white';
                                } else {
                                    input.checked = false;
                                    label.style.background = '';
                                    label.style.borderColor = '#e5e7eb';
                                    label.style.color = '#4b5563';
                                }
                            });
                        }

                        function showImgPreview(input, previewId) {
                            const preview = document.getElementById(previewId);
                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        @if($isSuperAdmin)
                        selectLevel('{{ old('degree_level', $program->degree_level) }}');
                        @endif
                    </script>

                </div>{{-- end centered form --}}
            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
