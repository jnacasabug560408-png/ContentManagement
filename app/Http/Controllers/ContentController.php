<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ContentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admins see all posts, authors only see their own
        $contents = $user->role === 'admin'
            ? Content::latest()->paginate(15)
            : Content::where('user_id', $user->id)->latest()->paginate(15);

        // Pass these so the Create modal dropdown works
        $categories = Category::all();
        $tags = Tag::all();

        return view('contents.index', compact('contents', 'categories', 'tags'));
    }

    public function publicIndex()
    {
        $contents = Content::with('category', 'tags')->latest()->paginate(12);
        $categories = Category::all();
        $tags = Tag::all();
        return view('contents.public-index', compact('contents', 'categories', 'tags'));
    }

    public function create()
    {
        Gate::authorize('canCreateContent');
        $categories = Category::all();
        $tags = Tag::all();
        return view('contents.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        Gate::authorize('canCreateContent');

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:10',
            'category_id' => 'nullable|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'status'      => 'required|in:draft,published,hidden',
        ]);

        $validated['slug']    = Str::slug($validated['title']);
        $validated['user_id'] = Auth::id();

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $content = Content::create($validated);

        if (!empty($validated['tags'])) {
            $content->tags()->attach($validated['tags']);
        }

        return redirect()->route('contents.index')
            ->with('success', 'Content created successfully!');
    }

    public function show(Content $content, Request $request)
{
    $content->load(['category', 'tags', 'user']);

    // Track view — once per IP per hour
    $cacheKey = 'content_view_' . $content->id . '_' . $request->ip();
    if (!\Cache::has($cacheKey)) {
        \Cache::put($cacheKey, true, now()->addHours(1));
        \Cache::increment('content_views_total_' . $content->id);
    }

    $viewCount = \Cache::get('content_views_total_' . $content->id, 0);

    return view('contents.show', compact('content', 'viewCount'));
}

    public function edit(Content $content)
    {
        // Fixed: replaced isAdmin() with role check
        if (Auth::id() !== $content->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $categories  = Category::all();
        $tags        = Tag::all();
        $selectedTags = $content->tags->pluck('id')->toArray();

        return view('contents.edit', compact('content', 'categories', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Content $content)
    {
        // Fixed: replaced isAdmin() with role check
        if (Auth::id() !== $content->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:10',
            'category_id' => 'nullable|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'status'      => 'required|in:draft,published,hidden',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !$content->published_at) {
            $validated['published_at'] = now();
        }

        $content->update($validated);
        $content->tags()->sync($request->tags ?? []);

        return redirect()->route('contents.index')
            ->with('success', 'Content updated successfully!');
    }

    public function destroy(Content $content)
    {
        // Fixed: replaced isAdmin() with role check
        if (Auth::id() !== $content->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $content->tags()->detach();
        $content->delete();

        return redirect()->route('contents.index')
            ->with('success', 'Content deleted successfully!');
    }

    public function moderation()
    {
        Gate::authorize('admin');
        $contents = Content::with('user')->latest()->paginate(15);
        $news = \App\Models\News::with(['user', 'comments'])->latest()->paginate(15);
        return view('contents.moderation', compact('contents', 'news'));
    }

    public function updateStatus(Request $request, Content $content)
{
    Gate::authorize('admin');

    $request->validate([
        'status' => 'required|in:draft,published,hidden',
    ]);

    $content->update([
        'status' => $request->status,
        'published_at' => $request->status === 'published' ? ($content->published_at ?? now()) : $content->published_at,
    ]);

    return back()->with('success', 'Status updated to ' . ucfirst($request->status) . '.');
}
}