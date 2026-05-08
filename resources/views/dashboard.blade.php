@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- ================================ --}}
    {{-- ADMIN DASHBOARD                  --}}
    {{-- ================================ --}}
    @if(Auth::user()->isAdmin())

        {{-- Pending Approval Alert --}}
        @php $pendingDrafts = \App\Models\Content::where('status','draft')->count(); @endphp
        @if($pendingDrafts > 0)
        <div class="alert-bar d-flex align-items-center justify-content-between rounded-3 shadow-sm mb-4 px-4 py-3">
            <div class="d-flex align-items-center gap-3">
                <span class="pulse-dot"></span>
                <div>
                    <strong style="font-size:14px;">{{ $pendingDrafts }} post(s) pending approval</strong>
                    <div class="text-muted" style="font-size:12px;">Authors are waiting for their drafts to be reviewed</div>
                </div>
            </div>
            <a href="{{ route('contents.moderation') }}" class="btn btn-warning btn-sm rounded-pill px-4 fw-semibold">
                <i class="bi bi-shield-check me-1"></i> Review Now
            </a>
        </div>
        @endif

        {{-- Stat Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-users">
                    <div class="card-body text-white">
                        <h6>Total Users</h6>
                        <h3>{{ $totalUsers ?? 0 }}</h3>
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-posts">
                    <div class="card-body text-white">
                        <h6>Total Posts</h6>
                        <h3>{{ $totalContent ?? 0 }}</h3>
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-comments">
                    <div class="card-body text-white">
                        <h6>Total Comments</h6>
                        <h3>{{ $totalComments ?? 0 }}</h3>
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card" style="background: linear-gradient(135deg, #7c3aed, #a78bfa) !important;">
                    <div class="card-body text-white">
                        <h6>Total News</h6>
                        <h3>{{ $totalNews ?? 0 }}</h3>
                        <i class="bi bi-newspaper"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
            <div class="card-body py-3 px-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions
                </h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('contents.moderation') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="bi bi-shield-lock me-1"></i> Moderation
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-people me-1"></i> Manage Users
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-folder me-1"></i> Categories
                    </a>
                    <a href="{{ route('analytics') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="bi bi-bar-chart me-1"></i> Analytics
                    </a>
                    <a href="{{ route('comments.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="bi bi-chat-square-dots me-1"></i> Comments
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Posts + Recent Activity --}}
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-clock-history text-primary me-2"></i>Recent Articles
                        </h5>
                        <a href="{{ route('contents.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                    </div>
                    {{-- Status summary bar --}}
                    <div class="d-flex gap-3 px-4 py-2 border-bottom bg-light" style="font-size:12px;">
                        <span><span class="badge bg-success me-1">{{ \App\Models\Content::where('status','published')->count() }}</span> Published</span>
                        <span><span class="badge bg-warning text-dark me-1">{{ \App\Models\Content::where('status','draft')->count() }}</span> Draft</span>
                        <span><span class="badge bg-secondary me-1">{{ \App\Models\Content::where('status','hidden')->count() }}</span> Hidden</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentContent ?? [] as $content)
                                        <tr>
                                            <td class="fw-medium">{{ Str::limit($content->title, 40) }}</td>
                                            <td class="text-muted" style="font-size:13px;">{{ $content->user->name }}</td>
                                            <td>
                                                @if($content->status === 'published')
                                                    <span class="badge rounded-pill bg-success">Published</span>
                                                @elseif($content->status === 'draft')
                                                    <span class="badge rounded-pill bg-warning text-dark">Draft</span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-muted" style="font-size:13px;">{{ $content->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-4">No posts yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-activity text-danger me-2"></i>Recent Activity
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentContent ?? [] as $item)
                        <div class="d-flex align-items-center gap-3 px-3 py-3 border-bottom">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                 style="width:38px;height:38px;font-size:13px;flex-shrink:0;">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:13px;">{{ $item->user->name }}</div>
                                <div class="text-muted text-truncate" style="font-size:12px;">{{ Str::limit($item->title, 35) }}</div>
                            </div>
                            <div class="text-muted text-nowrap" style="font-size:11px;">{{ $item->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-5">No recent activity</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- *** Recent News Table *** --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-broadcast-fill text-info me-2"></i>Recent News
                </h5>
                <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-info rounded-pill">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Likes</th>
                                <th>Comments</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\News::with(['user','comments'])->latest()->limit(5)->get() as $news)
                            <tr>
                                <td class="fw-medium">{{ Str::limit($news->title, 40) }}</td>
                                <td class="text-muted" style="font-size:13px;">{{ $news->user->name }}</td>
                                <td>
                                    <span class="badge" style="background:#e0f2fe; color:#0284c7;">
                                        {{ $news->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center gap-1" style="font-size:13px;">
                                        <i class="bi bi-heart-fill text-danger"></i>
                                        {{ $news->likes_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center gap-1" style="font-size:13px;">
                                        <i class="bi bi-chat-fill text-info"></i>
                                        {{ $news->comments->count() }}
                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:13px;">
                                    {{ $news->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-newspaper d-block fs-2 mb-2 opacity-25"></i>
                                    No news yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ================================ --}}
    {{-- AUTHOR DASHBOARD                 --}}
    {{-- ================================ --}}
    @elseif(Auth::user()->isAuthor())

        <div class="row mb-4">
            <div class="col-lg col-md-6 mb-3">
                <div class="card stat-card card-total-users">
                    <div class="card-body text-white">
                        <h6>My Articles</h6>
                        <h3>{{ $myContent ?? 0 }}</h3>
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-3">
                <div class="card stat-card card-total-posts">
                    <div class="card-body text-white">
                        <h6>My News</h6>
                        <h3>{{ $myNews ?? 0 }}</h3>
                        <i class="bi bi-broadcast"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-3">
                <div class="card stat-card card-pending-comments">
                    <div class="card-body text-white">
                        <h6>Drafts</h6>
                        <h3>{{ $myDrafts ?? 0 }}</h3>
                        <i class="bi bi-archive"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-3">
                <div class="card stat-card card-emerald">
                    <div class="card-body text-white">
                        <h6>Published</h6>
                        <h3>{{ $myPublished ?? 0 }}</h3>
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 mb-3">
                <div class="card stat-card card-total-comments">
                    <div class="card-body text-white">
                        <h6>My Comments</h6>
                        <h3>{{ $myComments ?? 0 }}</h3>
                        <i class="bi bi-chat-dots"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 mb-4" style="border-radius:16px;">
            <div class="card-body py-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi text-warning me-2"></i>Quick Actions
                </h6>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('contents.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-pencil-square me-2"></i> Write Article
                    </a>
                    <a href="{{ route('news.create') }}" class="btn btn-info text-white rounded-pill px-4">
                        <i class="bi bi-broadcast me-2"></i> Write News
                    </a>
                    <a href="{{ route('contents.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-list-ul me-2"></i> My Articles
                    </a>
                    <a href="{{ route('news.my') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-newspaper me-2"></i> My News
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi text-primary me-2"></i>My Recent Articles
                </h5>
                <a href="{{ route('contents.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Title</th><th>Status</th><th>Created</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentContent ?? [] as $content)
                                <tr>
                                    <td class="fw-medium">{{ Str::limit($content->title, 60) }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $content->status === 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($content->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $content->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No articles yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-broadcast-fill text-info me-2"></i>My Recent News
                </h5>
                <a href="{{ route('news.my') }}" class="btn btn-sm btn-outline-info rounded-pill">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Title</th><th>Category</th><th>Likes</th><th>Comments</th><th>Created</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentNews ?? [] as $news)
                                <tr>
                                    <td class="fw-medium">{{ Str::limit($news->title, 50) }}</td>
                                    <td><span class="badge bg-light text-primary border">{{ $news->category }}</span></td>
                                    <td><i class="bi bi-heart-fill text-danger"></i> {{ $news->likes_count }}</td>
                                    <td><i class="bi bi-chat-fill text-info"></i> {{ $news->comments->count() }}</td>
                                    <td class="text-muted">{{ $news->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No news posts yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ================================ --}}
    {{-- EDITOR / OTHER ROLES             --}}
    {{-- ================================ --}}
    @else
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-users">
                    <div class="card-body text-white">
                        <h6>My Posts</h6>
                        <h3>{{ $myContent ?? 0 }}</h3>
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-posts">
                    <div class="card-body text-white">
                        <h6>Drafts</h6>
                        <h3>{{ $myDrafts ?? 0 }}</h3>
                        <i class="bi bi-archive"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-pending-comments">
                    <div class="card-body text-white">
                        <h6>Published</h6>
                        <h3>{{ $myPublished ?? 0 }}</h3>
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card card-total-comments">
                    <div class="card-body text-white">
                        <h6>My Comments</h6>
                        <h3>{{ $myComments ?? 0 }}</h3>
                        <i class="bi bi-chat-dots"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<style>
:root {
    --card-blue: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    --card-cyan: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    --card-rose: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
    --card-emerald: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card {
    border: none;
    border-radius: 16px;
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}
.card-total-users      { background: var(--card-blue) !important; }
.card-total-posts      { background: var(--card-cyan) !important; }
.card-total-comments   { background: var(--card-rose) !important; }
.card-pending-comments { background: var(--card-emerald) !important; }
.card-emerald          { background: var(--card-emerald) !important; }
.stat-card h6 {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    opacity: 0.8;
}
.stat-card h3 { font-size: 2rem; font-weight: 700; }
.stat-card i {
    position: absolute;
    right: 20px;
    bottom: 15px;
    font-size: 2.5rem;
    opacity: 0.2;
}

.alert-bar {
    background: #fffbeb;
    border: 1px solid #fcd34d;
}

.pulse-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #f59e0b;
    display: inline-block;
    flex-shrink: 0;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.8); }
}
</style>
@endsection