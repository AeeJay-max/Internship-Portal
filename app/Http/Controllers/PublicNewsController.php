<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class PublicNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $articles = $query->paginate(9)->withQueryString();

        return view('news.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = News::published()->where('slug', $slug)->firstOrFail();

        // 3 related articles from same category, excluding current
        $related = News::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('news.show', compact('article', 'related'));
    }
}
