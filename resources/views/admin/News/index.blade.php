@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-8xl mx-auto px-6">
            <div class="flex gap-6">

                @include('admin.partials.sidebar')

                <div class="flex-1 min-w-0">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">News & Announcements</h1>
                            <p class="text-gray-500 text-sm mt-1">Manage articles and control which ones appear in the home page slider</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
                            <a href="{{ route('admin.news.create') }}"
                               class="px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-all hover:shadow-md"
                               style="background: #011C3E;">
                                + Add Article
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
                    @endif

                    {{-- HOME SLIDER PREVIEW — same pattern as programs --}}
                    <div class="bg-white rounded-2xl shadow border border-blue-100 p-6 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Home Page News Slider
                                </h2>
                                <p class="text-xs text-gray-400 mt-0.5">Click ★ on any article below to add or remove it from the home page slider. Max 8.</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full" style="background: #eff6ff; color: #1d4ed8;">
                                {{ $featured->count() }} / 8 shown
                            </span>
                        </div>

                        @if($featured->isEmpty())
                            <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-400">
                                No articles in the slider yet. Use the ★ button in the table below to add articles.
                            </div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
                                @foreach($featured as $f)
                                    <div class="rounded-xl overflow-hidden border border-gray-200">
                                        <div class="h-16 relative overflow-hidden">
                                            @if($f->image_path)
                                                <div class="absolute inset-0 bg-cover bg-center"
                                                     style="background-image: url('{{ asset('storage/' . $f->image_path) }}');"></div>
                                                <div class="absolute inset-0" style="background: rgba(1,28,62,0.45);"></div>
                                            @else
                                                <div class="absolute inset-0" style="background: linear-gradient(135deg, #011C3E, #611818);"></div>
                                            @endif
                                            <div class="absolute inset-0 flex items-center justify-center p-1.5">
                                                <p class="text-white text-xs font-semibold leading-tight text-center line-clamp-2">{{ $f->title }}</p>
                                            </div>
                                        </div>
                                        <div class="px-2 py-1.5 flex items-center justify-between bg-gray-50 gap-1">
                                            <span class="text-xs font-bold px-1.5 py-0.5 rounded-full capitalize"
                                                  style="background: #eff6ff; color: #1d4ed8; font-size: 10px;">{{ $f->category }}</span>
                                            <form method="POST" action="{{ route('admin.news.toggleFeatured', $f) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium transition">✕</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ALL ARTICLES TABLE --}}
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cover</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Published</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Author</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slider</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse($news as $article)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Cover thumbnail --}}
                                    <td class="px-4 py-3">
                                        <div class="w-16 h-10 rounded-lg overflow-hidden border border-gray-200 shrink-0">
                                            @if($article->image_path)
                                                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300" style="background:#f0f4f8;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Title + excerpt --}}
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-800">{{ $article->title }}</div>
                                        @if($article->excerpt)
                                            <div class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($article->excerpt, 70) }}</div>
                                        @else
                                            <div class="text-xs text-gray-300 mt-0.5 italic">No excerpt</div>
                                        @endif
                                    </td>

                                    {{-- Category --}}
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 capitalize">
                                            {{ $article->category }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3">
                                        @if($article->is_published)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Published</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Draft</span>
                                        @endif
                                    </td>

                                    {{-- Published date --}}
                                    <td class="px-4 py-3 text-gray-500 text-xs">
                                        {{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}
                                    </td>

                                    {{-- Author --}}
                                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $article->author?->name ?? '—' }}</td>

                                    {{-- Featured toggle (★) — navy when featured, gray when not --}}
                                    <td class="px-4 py-3">
                                        @if($article->is_published)
                                            <form method="POST" action="{{ route('admin.news.toggleFeatured', $article) }}">
                                                @csrf
                                                <button type="submit" class="text-xl transition-all"
                                                        title="{{ $article->is_featured ? 'Remove from home slider' : 'Add to home slider' }}">
                                                    @if($article->is_featured)
                                                        <span style="color: #011C3E;">★</span>
                                                    @else
                                                        <span class="text-gray-300 hover:text-gray-500">☆</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-200 text-xl" title="Publish article first">★</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('news.show', $article->slug) }}"
                                               target="_blank"
                                               class="text-xs font-medium text-gray-500 hover:text-gray-700">View</a>
                                            <a href="{{ route('admin.news.edit', $article) }}"
                                               class="text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                            <form method="POST" action="{{ route('admin.news.destroy', $article) }}"
                                                  onsubmit="return confirm('Delete this article?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-500 hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">No news articles yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $news->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
