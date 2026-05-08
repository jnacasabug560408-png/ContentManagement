@extends('layouts.app')
@section('title', $news->title)
@section('page-title', 'News')

@section('styles')
<style>
    .news-article-wrap { max-width: 820px; margin: 0 auto; }

    .article-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        margin-bottom: 24px;
    }

    .article-hero-img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
    }

    .article-body { padding: 36px 40px; }

    .article-category {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 16px;
    }

    .article-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 20px;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 28px;
    }

    .author-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }

    .article-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #334155;
        white-space: pre-wrap;
    }

    .article-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .like-btn {
        background: none;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .like-btn:hover { border-color: #f43f5e; color: #f43f5e; }
    .like-btn.liked { border-color: #f43f5e; color: #f43f5e; background: #fff1f2; }

    .comments-card {
        background: white;
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }

    .comments-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comment-item {
        display: flex;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .comment-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    .comment-bubble {
        background: #f8fafc;
        border-radius: 0 14px 14px 14px;
        padding: 12px 16px;
        flex: 1;
    }

    .comment-user {
        font-weight: 700;
        font-size: 13px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }

    .comment-role {
        background: #e0f2fe;
        color: #0284c7;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        letter-spacing: 0.5px;
    }

    .comment-text { color: #334155; font-size: 14px; line-height: 1.6; }
    .comment-time { font-size: 11px; color: #94a3b8; margin-top: 4px; }

    .comment-form-wrap {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .comment-input {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 16px;
        font-size: 14px;
        width: 100%;
        resize: none;
        transition: border-color 0.2s;
        min-height: 90px;
    }

    .comment-input:focus {
        border-color: #38bdf8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
    }

    .btn-comment {
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 14px;
        transition: opacity 0.2s;
        margin-top: 10px;
        cursor: pointer;
    }

    .btn-comment:hover { opacity: 0.9; color: white; }
    .btn-comment:disabled { opacity: 0.6; cursor: not-allowed; }

    .delete-comment-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 12px;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
        transition: color 0.2s;
    }

    .delete-comment-btn:hover { color: #ef4444; }
</style>
@endsection

@section('content')
<div class="news-article-wrap">

    {{-- Back --}}
    <a href="{{ route('news.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill mb-4">
        <i class="bi bi-arrow-left"></i> Back to News
    </a>

    {{-- Article Card --}}
    <div class="article-card">

        @if($news->photo)
            <img src="{{ $news->photo }}"
                 alt="{{ $news->title }}" class="article-hero-img">
        @endif

        <div class="article-body">
            <span class="article-category">{{ $news->category }}</span>
            <h1 class="article-title">{{ $news->title }}</h1>

            <div class="article-meta">
                <div class="author-avatar">{{ substr($news->user->name, 0, 1) }}</div>
                <div>
                    <div class="fw-semibold text-dark" style="font-size:14px;">
                        {{ $news->user->name }}
                    </div>
                    <div class="text-muted" style="font-size:12px;">
                        {{ $news->created_at->format('F d, Y · g:i A') }}
                    </div>
                </div>

                @if(Auth::id() === $news->user_id || Auth::user()->isAdmin())
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('news.edit', $news) }}"
                       class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('news.destroy', $news) }}" method="POST"
                          onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <div class="article-content">{{ $news->body }}</div>

            {{-- Like Action --}}
            <div class="article-actions">
                <button class="like-btn {{ $liked ? 'liked' : '' }}"
                        id="like-btn"
                        onclick="toggleLike({{ $news->id }})">
                    <i class="bi {{ $liked ? 'bi-heart-fill' : 'bi-heart' }}" id="like-icon"></i>
                    <span id="like-count">{{ $news->likes_count }}</span> Likes
                </button>

                <span class="text-muted" style="font-size:13px;">
                    <i class="bi bi-chat-fill text-info me-1"></i>
                    <span id="comment-count">{{ $news->comments->count() }}</span> Comment(s)
                </span>

                {{-- 👁 View Counter --}}
                <span class="text-muted" style="font-size:13px;">
                    <i class="bi bi-eye-fill text-primary me-1"></i>
                    {{ number_format($viewCount) }} {{ $viewCount == 1 ? 'View' : 'Views' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="comments-card">
        <div class="comments-title">
            <i class="bi bi-chat-square-text-fill text-info"></i>
            Comments
            <span class="badge bg-light text-secondary border"
                  style="font-size:12px;" id="comment-count-badge">
                {{ $news->comments->count() }}
            </span>
        </div>

        {{-- Existing Comments --}}
        <div id="comments-list">
            @forelse($news->comments as $comment)
            <div class="comment-item" id="comment-{{ $comment->id }}">
                <div class="comment-avatar">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                <div class="comment-bubble">
                    <div class="comment-user">
                        {{ $comment->user->name }}
                        <span class="comment-role">{{ strtoupper($comment->user->role) }}</span>
                        @if($comment->user_id === Auth::id() || Auth::user()->isAdmin())
                        <form action="{{ route('news.comment.delete', $comment) }}"
                              method="POST" class="ms-auto">
                            @csrf @method('DELETE')
                            <button class="delete-comment-btn" title="Delete comment">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    <div class="comment-text">{{ $comment->body }}</div>
                    <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-3" id="no-comments-msg">
                No comments yet. Be the first to comment!
            </p>
            @endforelse
        </div>

        {{-- Add Comment Form --}}
        <div class="comment-form-wrap">
            <div class="d-flex gap-3">
                <div class="comment-avatar"
                     style="background: linear-gradient(135deg, #0ea5e9, #2563eb);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-grow-1">
                    <textarea class="comment-input"
                              id="comment-body"
                              placeholder="Write a comment..."></textarea>
                    <button class="btn-comment"
                            id="comment-submit-btn"
                            onclick="postComment({{ $news->id }})">
                        <i class="bi bi-send-fill me-1"></i> Post Comment
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Toggle Like
function toggleLike(newsId) {
    fetch(`/news/${newsId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('like-count').textContent = data.likes_count;
        const btn = document.getElementById('like-btn');
        const icon = document.getElementById('like-icon');
        if (data.liked) {
            btn.classList.add('liked');
            icon.className = 'bi bi-heart-fill';
        } else {
            btn.classList.remove('liked');
            icon.className = 'bi bi-heart';
        }
    })
    .catch(err => console.error('Like error:', err));
}

// Post Comment via AJAX
function postComment(newsId) {
    const bodyEl = document.getElementById('comment-body');
    const body = bodyEl.value.trim();
    if (!body) {
        bodyEl.focus();
        return;
    }

    const btn = document.getElementById('comment-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Posting...';

    fetch(`/news/${newsId}/comment`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ body })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const c = data.comment;

            // Remove "no comments" message if present
            const noMsg = document.getElementById('no-comments-msg');
            if (noMsg) noMsg.remove();

            // Build new comment HTML
            const html = `
            <div class="comment-item" id="comment-${c.id}">
                <div class="comment-avatar">${c.avatar}</div>
                <div class="comment-bubble">
                    <div class="comment-user">
                        ${c.user_name}
                        <span class="comment-role">${c.user_role}</span>
                    </div>
                    <div class="comment-text">${c.body}</div>
                    <div class="comment-time">${c.created_at}</div>
                </div>
            </div>`;

            // Add to top of comments list
            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', html);

            // Clear textarea
            bodyEl.value = '';

            // Update comment counts
            const countEl = document.getElementById('comment-count');
            const countBadge = document.getElementById('comment-count-badge');
            if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
            if (countBadge) countBadge.textContent = parseInt(countBadge.textContent) + 1;
        }
    })
    .catch(err => console.error('Comment error:', err))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Post Comment';
    });
}
</script>
@endsection