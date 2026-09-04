@extends('layouts.app')

@section('content')

    {{-- Hero image or gradient --}}
    <div class="relative h-72 md:h-96 overflow-hidden">
        @if($article->image_path)
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('storage/' . $article->image_path) }}');"></div>
        @else
            <div class="absolute inset-0 flex items-center justify-center text-9xl"
                 style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                @if($article->category === 'events') 🎓
                @elseif($article->category === 'research') 🔬
                @elseif($article->category === 'academic') 📚
                @else 📰
                @endif
            </div>
        @endif
        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(1,22,39,0.85) 0%, rgba(1,22,39,0.3) 60%, transparent 100%);"></div>

        {{-- Back link --}}
        <div class="absolute top-6 left-6">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-white/80 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to News
            </a>
        </div>

        {{-- Category badge --}}
        <div class="absolute bottom-6 left-6 md:left-1/2 md:-translate-x-1/2 md:text-center">
            <span class="text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full"
                  style="background: #611818; color: white;">
                {{ $article->category }}
            </span>
        </div>
    </div>

    {{-- Article body --}}
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6">

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-6"
                style="color: #011C3E; font-family: 'Georgia', serif;">
                {{ $article->title }}
            </h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400 pb-6 mb-8 border-b border-gray-100">
                @if($article->published_at)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $article->published_at->format('d F Y') }}
                    </div>
                @endif
                @if($article->author)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $article->author->name }}
                    </div>
                @endif
            </div>

            {{-- Excerpt (lead) --}}
            @if($article->excerpt)
                <p class="text-lg leading-relaxed font-medium text-gray-700 mb-8 pl-4 border-l-4"
                   style="border-color: #611818;">
                    {{ $article->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            @if($article->body)
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed"
                     style="font-size: 1.05rem; line-height: 1.8;">
                    {!! nl2br(e($article->body)) !!}
                </div>
            @else
                <p class="text-gray-500 italic">Full article content coming soon.</p>
            @endif

            {{-- Share --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-4">
                <span class="text-sm font-semibold text-gray-500">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-80"
                   style="background: #1877f2;">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                   target="_blank" rel="noopener"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-80"
                   style="background: #1da1f2;">
                    Twitter
                </a>
                <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.textContent='Copied!';"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all border"
                        style="border-color: #011C3E; color: #011C3E;">
                    Copy Link
                </button>
            </div>

        </div>
    </section>

    {{-- More articles --}}
    @if($related->isNotEmpty())
        <section class="py-16" style="background: #f0f4f8;">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-2xl font-bold mb-8" style="color: #011C3E; font-family: 'Georgia', serif;">
                    More Articles
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}"
                           class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="h-40 overflow-hidden relative">
                                @if($rel->image_path)
                                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                                         style="background-image: url('{{ asset('storage/' . $rel->image_path) }}');"></div>
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-4xl"
                                         style="background: linear-gradient(135deg, #011C3E 0%, #611818 100%);">
                                        📰
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded"
                                      style="background: #f0f4f8; color: #611818;">{{ $rel->category }}</span>
                                <h3 class="font-bold mt-2 leading-snug line-clamp-2 text-sm" style="color: #011C3E;">
                                    {{ $rel->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
