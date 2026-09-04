@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">
                    <div class="max-w-3xl mx-auto">

                        <div class="flex items-center gap-4 mb-8">
                            <a href="{{ route('admin.news.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
                            <h1 class="text-2xl font-bold text-blue-900">New Article</h1>
                        </div>

                        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data"
                              class="bg-white rounded-xl shadow p-8 space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5" required>
                                        <option value="general" @selected(old('category') == 'general')>General</option>
                                        <option value="academic" @selected(old('category') == 'academic')>Academic</option>
                                        <option value="research" @selected(old('category') == 'research')>Research</option>
                                        <option value="events" @selected(old('category') == 'events')>Events</option>
                                    </select>
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_published" value="1" class="w-5 h-5 rounded text-blue-600" @checked(old('is_published'))>
                                        <span class="text-sm font-medium text-gray-700">Publish immediately</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt <span class="text-gray-400 font-normal">(shown in news ticker)</span></label>
                                <textarea name="excerpt" rows="2"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5">{{ old('excerpt') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Content</label>
                                <textarea name="body" rows="8"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5">{{ old('body') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                                <input type="file" name="image" accept="image/*"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                            </div>

                            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-semibold hover:bg-blue-800 transition">Save Article</button>
                            </div>
                        </form>
                    </div>

                </div>{{-- end centered form --}}
            </div>{{-- end main content --}}
        </div>{{-- end flex --}}
    </div>
    </div>
@endsection
