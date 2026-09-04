@extends('layouts.app')

@section('content')

    {{-- Hero --}}
    <section class="relative py-24 overflow-hidden" style="background: #011C3E;">
        <div class="absolute inset-0 bg-cover bg-center opacity-10"
             style="background-image: url('/images/hero/MOSRAC-hero.jpg');"></div>
        <img src="/images/branding/MOSRAC.svg"
             class="absolute pointer-events-none select-none opacity-5"
             style="width: 400px; right: 5%; top: 50%; transform: translateY(-50%);" alt="">
        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-semibold tracking-widest uppercase mb-4" style="color: rgba(255,255,255,0.5);">
                National Internship Portal of Armenia
            </p>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4" style="font-family: 'Georgia', serif;">
                {{ __('nav.news') }} & Announcements
            </h1>
            <div class="w-16 h-1 mx-auto rounded" style="background: #611818;"></div>
        </div>
    </section>

    {{-- Category filter --}}
    <div style="background: #f0f4f8;" class="border-b border-gray-200 sticky top-20 md:top-24 z-30">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">
                @php
                    $categories = ['all', 'general', 'events', 'research', 'academic'];
                    $current    = request('category', 'all');
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('news.index', $cat !== 'all' ? ['category' => $cat] : []) }}"
                       class="shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wide transition-all duration-150
                              {{ $current === $cat
                                  ? 'text-white'
                                  : 'text-gray-500 hover:text-gray-800' }}"
                       style="{{ $current === $cat ? 'background:#011C3E;' : 'background: white; border: 1px solid #e5e7eb;' }}">
                        {{ ucfirst($cat) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Articles grid --}}
    <section class="py-16" style="background: #f0f4f8;">
        <div class="max-w-7xl mx-auto px-6">

            @if($articles->isEmpty())
                <div class="text-center py-24">
                    <div class="text-6xl mb-4">📰</div>
                    <p class="text-gray-500 text-lg">No articles found.</p>
                    <a href="{{ route('news.index') }}" class="mt-4 inline-block text-sm font-semibold" style="color:#611818;">
                        View all news →
                    </a>
                </div>
            @else
                {{-- Featured article (first one) --}}
                @php $featured = $articles->first(); @endphp
                <a href="{{ route('news.show', $featured->slug) }}"
                   class="group block bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 mb-10">
                    <div class="grid md:grid-cols-2">
                        <div class="relative h-64 md:h-auto overflow-hidden">
                            @if($featured->image_path)
                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                                     style="background-image: url('{{ asset('storage/' . $featured->image_path) }}');"></div>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-7xl"
                                     style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                    @if($featured->category === 'events') 🎓
                                    @elseif($featured->category === 'research') 🔬
                                    @elseif($featured->category === 'academic') 📚
                                    @else 📰
                                    @endif
                                </div>
                            @endif
                            <div class="absolute inset-0" style="background: rgba(1,28,62,0.2);"></div>
                            <span class="absolute top-4 left-4 text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full"
                                  style="background: #611818; color: white;">
                                Featured
                            </span>
                        </div>
                        <div class="p-8 md:p-10 flex flex-col justify-center">
                            <span class="text-xs font-semibold uppercase tracking-wide px-2 py-1 rounded inline-block mb-4"
                                  style="background: #f0f4f8; color: #611818;">
                                {{ $featured->category }}
                            </span>
                            <h2 class="text-2xl font-bold mb-3 leading-tight" style="color: #011C3E; font-family: 'Georgia', serif;">
                                {{ $featured->title }}
                            </h2>
                            @if($featured->excerpt)
                                <p class="text-gray-600 leading-relaxed mb-5">{{ $featured->excerpt }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                @if($featured->published_at)
                                    <span>{{ $featured->published_at->format('d M Y') }}</span>
                                @endif
                                @if($featured->author)
                                    <span>by {{ $featured->author->name }}</span>
                                @endif
                            </div>
                            <div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold" style="color: #611818;">
                                Read Full Article
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Rest of articles --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($articles->skip(1) as $article)
                        <a href="{{ route('news.show', $article->slug) }}"
                           class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">
                            <div class="relative h-44 overflow-hidden">
                                @if($article->image_path)
                                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                                         style="background-image: url('{{ asset('storage/' . $article->image_path) }}');"></div>
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-5xl"
                                         style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                        @if($article->category === 'events') 🎓
                                        @elseif($article->category === 'research') 🔬
                                        @elseif($article->category === 'academic') 📚
                                        @else 📰
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-semibold uppercase tracking-wide px-2 py-1 rounded"
                                          style="background: #f0f4f8; color: #611818;">
                                        {{ $article->category }}
                                    </span>
                                    @if($article->published_at)
                                        <span class="text-xs text-gray-400">{{ $article->published_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                                <h3 class="font-bold mb-2 leading-tight line-clamp-2 flex-1" style="color: #011C3E; font-family: 'Georgia', serif;">
                                    {{ $article->title }}
                                </h3>
                                @if($article->excerpt)
                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-2 mb-4">{{ $article->excerpt }}</p>
                                @endif
                                <div class="mt-auto inline-flex items-center gap-1.5 text-sm font-semibold" style="color: #611818;">
                                    Read More
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($articles->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $articles->links() }}
                    </div>
                @endif
            @endif

        </div>
    </section>

@endsection
