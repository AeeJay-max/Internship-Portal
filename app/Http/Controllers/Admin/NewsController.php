<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news     = News::with('author')->latest()->paginate(20);
        $featured = News::featured()->limit(8)->get();
        return view('admin.News.index', compact('news', 'featured'));
    }

    public function create()
    {
        return view('admin.News.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'body'         => 'nullable|string',
            'category'     => 'required|in:general,academic,research,events',
            'is_published' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        News::create([
            'title'        => $validated['title'],
            'slug'         => News::generateSlug($validated['title']),
            'excerpt'      => $validated['excerpt'] ?? null,
            'body'         => $validated['body'] ?? null,
            'image_path'   => $imagePath,
            'category'     => $validated['category'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
            'created_by'   => Auth::id(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News article published successfully.');
    }

    public function edit(News $news)
    {
        return view('admin.News.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'body'         => 'nullable|string',
            'category'     => 'required|in:general,academic,research,events',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($news->image_path) Storage::disk('public')->delete($news->image_path);
            $validated['image_path'] = $request->file('image')->store('news', 'public');
        }

        $wasPublished = $news->is_published;
        $news->update([
            'title'        => $validated['title'],
            'slug'         => $news->slug,
            'excerpt'      => $validated['excerpt'] ?? null,
            'body'         => $validated['body'] ?? null,
            'image_path'   => $validated['image_path'] ?? $news->image_path,
            'category'     => $validated['category'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => (!$wasPublished && $request->boolean('is_published')) ? now() : $news->published_at,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News article updated.');
    }

    public function destroy(News $news)
    {
        if ($news->image_path) Storage::disk('public')->delete($news->image_path);
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News article deleted.');
    }

    public function toggleFeatured(News $news)
    {
        // Max 8 featured news articles at a time
        if (!$news->is_featured && News::featured()->count() >= 8) {
            return back()->with('error', 'Maximum 8 featured articles allowed. Remove one first.');
        }

        $news->update(['is_featured' => !$news->is_featured]);

        return back()->with('success', $news->is_featured
            ? "'{$news->title}' added to home slider."
            : "'{$news->title}' removed from home slider."
        );
    }
}
