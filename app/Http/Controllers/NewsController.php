<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use App\Models\NewsLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    // Public feed - all users can see
    public function index()
    {
        $news = News::with(['user', 'comments', 'likes'])
            ->latest()
            ->paginate(10);

        return view('news.index', compact('news'));
    }

    // Author's own news only
    public function myNews()
    {
        $news = News::where('user_id', Auth::id())
            ->with(['comments', 'likes'])
            ->latest()
            ->paginate(10);

        return view('news.my-news', compact('news'));
    }

    // Show create form
    public function create()
    {
        $this->authorOnly();
        return view('news.create');
    }

    // Store new news post
    public function store(Request $request)
    {
        $this->authorOnly();

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'category' => 'nullable|string|max:100',
            'photo'    => 'nullable|string', // now a URL from Cloudinary
        ]);

        News::create([
            'user_id'  => Auth::id(),
            'title'    => $validated['title'],
            'body'     => $validated['body'],
            'category' => $validated['category'] ?? 'General',
            'photo'    => $request->input('photo') ?: null,
        ]);

        return redirect()->route('news.my')->with('success', 'News post published successfully!');
    }

    // Show single news post
    public function show(News $news, Request $request)
    {
        $news->load(['user', 'comments.user', 'likes']);

        // Track view using cache — once per IP per hour
        $cacheKey = 'news_view_' . $news->id . '_' . $request->ip();
        if (!\Cache::has($cacheKey)) {
            \Cache::put($cacheKey, true, now()->addHours(1));
            \Cache::increment('news_views_total_' . $news->id);
        }

        $viewCount = \Cache::get('news_views_total_' . $news->id, 0);
        $liked = Auth::check() ? $news->isLikedBy(Auth::id()) : false;

        return view('news.show', compact('news', 'liked', 'viewCount'));
    }

    // Show edit form
    public function edit(News $news)
    {
        $this->authorOnly();
        $this->ownerOnly($news);

        return view('news.edit', compact('news'));
    }

    // Update news post
    public function update(Request $request, News $news)
    {
        $this->authorOnly();
        $this->ownerOnly($news);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'category' => 'nullable|string|max:100',
            'photo'    => 'nullable|string', // now a URL from Cloudinary
        ]);

        // Only update photo if a new one was uploaded
        if ($request->filled('photo')) {
            $validated['photo'] = $request->input('photo');
        } else {
            // Keep existing photo
            unset($validated['photo']);
        }

        $news->update($validated);

        return redirect()->route('news.my')->with('success', 'News post updated successfully!');
    }

    // Delete news post
    public function destroy(News $news)
    {
        $this->authorOnly();
        $this->ownerOnly($news);

        $news->delete();

        return redirect()->route('news.my')->with('success', 'News post deleted.');
    }

    // Toggle like
    public function like(News $news)
    {
        $userId = Auth::id();
        $existing = NewsLike::where('news_id', $news->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $news->decrement('likes_count');
            $liked = false;
        } else {
            NewsLike::create(['news_id' => $news->id, 'user_id' => $userId]);
            $news->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $news->fresh()->likes_count,
        ]);
    }

    // Add comment
    public function comment(Request $request, News $news)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $comment = NewsComment::create([
            'news_id' => $news->id,
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'id'         => $comment->id,
                'body'       => e($comment->body),
                'user_name'  => e($comment->user->name),
                'user_role'  => strtoupper($comment->user->role),
                'avatar'     => strtoupper(substr($comment->user->name, 0, 1)),
                'created_at' => 'just now',
            ],
        ]);
    }

    // Delete comment
    public function deleteComment(NewsComment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    // Helpers
    private function authorOnly()
    {
        if (!Auth::user()->isAuthor() && !Auth::user()->isAdmin()) {
            abort(403, 'Only authors can manage news.');
        }
    }

    private function ownerOnly(News $news)
    {
        if ($news->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You can only manage your own news.');
        }
    }
}