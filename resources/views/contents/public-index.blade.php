@extends('layouts.app')

@section('title', 'All Posts')
@section('page-title', 'Latest Posts')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;600;700&display=swap');

    .posts-wrap { font-family: 'DM Sans', sans-serif; }

    /* ── Filter Banner ── */
    .filter-banner {
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        font-size: 15px;
    }

    .filter-banner.cat { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1d4ed8; border: 1px solid #bfdbfe; }
    .filter-banner.tag { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #6d28d9; border: 1px solid #ddd6fe; }

    /* ── Post Cards ── */
    .post-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .post-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .post-card-top {
        height: 6px;
        width: 100%;
    }

    .post-card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .post-cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .post-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 10px;
        text-decoration: none;
        display: block;
        transition: color 0.2s;
    }

    .post-title:hover { color: #0ea5e9; }

    .post-excerpt {
        font-size: 13px;
        color: #64748b;
        line-height: 1.7;
        flex: 1;
        margin-bottom: 20px;
    }

    .post-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .post-author {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-avatar-sm {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        color: white;
        flex-shrink: 0;
    }

    .author-name-sm {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        line-height: 1.2;
    }

    .author-date-sm {
        font-size: 11px;
        color: #94a3b8;
    }

    .read-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        background: #0f172a;
        color: white;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .read-btn:hover {
        background: #0ea5e9;
        color: white;
        transform: translateX(2px);
    }

    /* ── Category color themes ── */
    .theme-education .post-card-top { background: linear-gradient(90deg, #3b82f6, #2563eb); }
    .theme-education .post-cat-badge { background: #eff6ff; color: #1d4ed8; }
    .theme-education .author-avatar-sm { background: linear-gradient(135deg, #3b82f6, #2563eb); }

    .theme-tech .post-card-top { background: linear-gradient(90deg, #06b6d4, #0284c7); }
    .theme-tech .post-cat-badge { background: #e0f2fe; color: #0284c7; }
    .theme-tech .author-avatar-sm { background: linear-gradient(135deg, #06b6d4, #0284c7); }

    .theme-lifestyle .post-card-top { background: linear-gradient(90deg, #10b981, #059669); }
    .theme-lifestyle .post-cat-badge { background: #dcfce7; color: #059669; }
    .theme-lifestyle .author-avatar-sm { background: linear-gradient(135deg, #10b981, #059669); }

    .theme-business .post-card-top { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .theme-business .post-cat-badge { background: #fef9c3; color: #b45309; }
    .theme-business .author-avatar-sm { background: linear-gradient(135deg, #f59e0b, #d97706); }

    .theme-health .post-card-top { background: linear-gradient(90deg, #f43f5e, #e11d48); }
    .theme-health .post-cat-badge { background: #fff1f2; color: #e11d48; }
    .theme-health .author-avatar-sm { background: linear-gradient(135deg, #f43f5e, #e11d48); }

    .theme-default .post-card-top { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .theme-default .post-cat-badge { background: #f5f3ff; color: #7c3aed; }
    .theme-default .author-avatar-sm { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
    }

    .empty-icon {
        width: 80px; height: 80px;
        border-radius: 20px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #cbd5e1;
        margin: 0 auto 16px;
    }

    /* ── Sidebar ── */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .sidebar-head {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-body { padding: 16px 18px; }

    /* ── Category Pills ── */
    .cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid transparent;
        transition: all 0.2s;
        margin: 3px;
    }

    .cat-pill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .cat-pill.active { outline: 2px solid currentColor; outline-offset: 2px; }

    .cat-count {
        font-size: 10px;
        background: rgba(255,255,255,0.5);
        padding: 1px 6px;
        border-radius: 10px;
        font-weight: 700;
    }

    /* ── Tag Pills ── */
    .tag-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin: 3px;
    }

    .tag-pill:hover {
        background: #0f172a !important;
        color: white !important;
        transform: rotate(-1deg);
    }

    /* ── Stats Mini — Analytics Style ── */
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .stat-mini-pill {
        padding: 16px 18px;
        border-right: 1px solid #dbeafe;
        text-align: left;
    }

    .stat-mini-pill:last-child {
        border-right: none;
    }

    .stat-mini-val {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 5px;
    }

    .stat-mini-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 posts-wrap">
    <div class="row">

        {{-- ── MAIN CONTENT ── --}}
        <div class="col-lg-8">

            {{-- Filter Banner --}}
            @if(isset($category))
            <div class="filter-banner cat">
                <i class="bi bi-folder2-open fs-5"></i>
                <span>Showing posts in: <strong>{{ $category->name }}</strong></span>
                <a href="{{ route('posts.public.index') }}" class="ms-auto btn btn-sm btn-outline-primary rounded-pill">
                    Clear Filter
                </a>
            </div>
            @elseif(isset($tag))
            <div class="filter-banner tag">
                <i class="bi bi-tag-fill fs-5"></i>
                <span>Posts tagged as: <strong>#{{ $tag->name }}</strong></span>
                <a href="{{ route('posts.public.index') }}" class="ms-auto btn btn-sm btn-outline-secondary rounded-pill">
                    Clear Filter
                </a>
            </div>
            @endif

            {{-- Posts Grid --}}
            <div class="row g-3">
                @forelse($contents as $content)
                @php
                    $catName = strtolower($content->category->name ?? '');
                    $theme = 'theme-default';
                    if(str_contains($catName, 'education')) $theme = 'theme-education';
                    elseif(str_contains($catName, 'tech')) $theme = 'theme-tech';
                    elseif(str_contains($catName, 'lifestyle')) $theme = 'theme-lifestyle';
                    elseif(str_contains($catName, 'business')) $theme = 'theme-business';
                    elseif(str_contains($catName, 'health')) $theme = 'theme-health';
                @endphp
                <div class="col-md-6">
                    <div class="{{ $theme }}">
                        <div class="post-card">
                            <div class="post-card-top"></div>
                            <div class="post-card-body">
                                {{-- Category --}}
                                <span class="post-cat-badge">
                                    <i class="bi bi-folder-fill" style="font-size:9px;"></i>
                                    {{ $content->category->name ?? 'Uncategorized' }}
                                </span>

                                {{-- Title --}}
                                <a href="{{ route('posts.public.show', $content->slug) }}" class="post-title">
                                    {{ Str::limit($content->title, 55) }}
                                </a>

                                {{-- Excerpt --}}
                                <p class="post-excerpt">
                                    {{ Str::limit(strip_tags($content->body), 110) }}
                                </p>

                                {{-- Footer --}}
                                <div class="post-footer">
                                    <div class="post-author">
                                        <div class="author-avatar-sm">
                                            {{ strtoupper(substr($content->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="author-name-sm">{{ $content->user->name ?? 'Author' }}</div>
                                            <div class="author-date-sm">{{ $content->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('posts.public.show', $content->slug) }}" class="read-btn">
                                        Read <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">No posts found</h5>
                        <p class="text-muted mb-4">There are no posts in this section yet.</p>
                        <a href="{{ route('posts.public.index') }}" class="btn btn-dark rounded-pill px-4">
                            <i class="bi bi-grid me-2"></i> View All Posts
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $contents->links() }}
            </div>
        </div>

        {{-- ── SIDEBAR ── --}}
        <div class="col-lg-4">

            {{-- ── Stats Mini — Analytics Style ── --}}
            <div class="stats-mini">
                <div class="stat-mini-pill">
                    <div class="stat-mini-val">{{ $contents->total() }}</div>
                    <div class="stat-mini-label">Total Posts</div>
                </div>
                <div class="stat-mini-pill">
                    <div class="stat-mini-val">{{ $categories->count() }}</div>
                    <div class="stat-mini-label">Categories</div>
                </div>
                <div class="stat-mini-pill">
                    <div class="stat-mini-val">{{ $tags->count() }}</div>
                    <div class="stat-mini-label">Tags</div>
                </div>
            </div>

            {{-- Categories --}}
            <div class="sidebar-card">
                <div class="sidebar-head">
                    <i class="bi bi-folder2-open text-primary"></i>
                    Categories
                </div>
                <div class="sidebar-body">
                    <div class="d-flex flex-wrap">
                        @foreach($categories as $cat)
                        @php
                            $cn = strtolower($cat->name);
                            $cs = 'background:#f5f3ff;color:#7c3aed;border-color:#ede9fe;';
                            if(str_contains($cn,'education')) $cs = 'background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;';
                            elseif(str_contains($cn,'tech')) $cs = 'background:#e0f2fe;color:#0284c7;border-color:#bae6fd;';
                            elseif(str_contains($cn,'lifestyle')) $cs = 'background:#dcfce7;color:#059669;border-color:#bbf7d0;';
                            elseif(str_contains($cn,'business')) $cs = 'background:#fef9c3;color:#b45309;border-color:#fde68a;';
                            elseif(str_contains($cn,'health')) $cs = 'background:#fff1f2;color:#e11d48;border-color:#fecdd3;';
                        @endphp
                        <a href="{{ route('categories.show', $cat->id) }}"
                           class="cat-pill {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}"
                           style="{{ $cs }}">
                            {{ $cat->name }}
                            <span class="cat-count">{{ $cat->contents_count ?? $cat->contents->count() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            <div class="sidebar-card">
                <div class="sidebar-head">
                    <i class="bi bi-tags text-primary"></i>
                    Popular Tags
                </div>
                <div class="sidebar-body">
                    <div class="d-flex flex-wrap">
                        @php
                            $tagStyles = [
                                'background:#eff6ff;color:#1d4ed8;',
                                'background:#dcfce7;color:#059669;',
                                'background:#e0f2fe;color:#0284c7;',
                                'background:#fff1f2;color:#e11d48;',
                                'background:#fef9c3;color:#b45309;',
                                'background:#f5f3ff;color:#7c3aed;',
                            ];
                        @endphp
                        @foreach($tags as $t)
                        <a href="{{ route('tags.show', $t->slug) }}"
                           class="tag-pill"
                           style="{{ $tagStyles[$loop->index % count($tagStyles)] }}">
                            #{{ $t->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection