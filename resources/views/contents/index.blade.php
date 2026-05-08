@extends('layouts.app')
@section('title', 'My Articles')
@section('page-title', 'My Articles')

@section('styles')
<style>
    :root {
        --navy: #0f172a;
        --navy-soft: #1e293b;
        --blue: #3b82f6;
        --blue-light: #60a5fa;
        --blue-glow: rgba(59,130,246,0.10);
        --slate: #64748b;
        --slate-light: #94a3b8;
        --border: #e2e8f0;
        --white: #ffffff;
        --bg: #f8fafc;
    }

    .articles-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 0 60px;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 28px;
    }

    .page-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--blue);
        margin-bottom: 6px;
        display: block;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--navy);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .page-sub {
        font-size: 13px;
        color: var(--slate);
        margin: 0;
    }

    .btn-create {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 11px 22px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
    }

    /* ── Alert ── */
    .alert-success-custom {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: #15803d;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    /* ── Main Card ── */
    .articles-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }

    .articles-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        border-radius: 18px 18px 0 0;
    }

    /* ── Table ── */
    .table { margin: 0; }

    .table thead th {
        background: var(--bg);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--slate);
        padding: 14px 20px;
        border: none;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border-color: var(--border);
        font-size: 13.5px;
    }

    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: var(--bg); }

    .article-title-cell {
        font-weight: 700;
        color: var(--navy);
        font-size: 14px;
        line-height: 1.3;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--slate);
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 3px 10px;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 20px;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    .status-published { background: #dcfce7; color: #15803d; }
    .status-draft     { background: #fef9c3; color: #a16207; }
    .status-hidden    { background: #f1f5f9; color: #64748b; }

    .views-cell {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: var(--slate);
        font-weight: 500;
    }

    .views-cell i { font-size: 12px; color: var(--slate-light); }

    .date-cell {
        font-size: 12.5px;
        color: var(--slate-light);
        white-space: nowrap;
    }

    /* ── Action Buttons ── */
    .action-group { display: flex; gap: 6px; align-items: center; }

    .action-btn {
        width: 32px; height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .action-btn:hover { transform: translateY(-2px); }
    .btn-view   { background: #e0f2fe; color: #0284c7; }
    .btn-edit   { background: #fef9c3; color: #ca8a04; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-view:hover   { background: #bae6fd; color: #0369a1; }
    .btn-edit:hover   { background: #fde68a; color: #92400e; }
    .btn-delete:hover { background: #fecaca; color: #b91c1c; }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
    }

    .empty-icon-wrap {
        width: 60px; height: 60px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: var(--slate-light);
        margin: 0 auto 16px;
    }

    .empty-state h5 {
        font-size: 15px;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 6px;
    }

    .empty-state p {
        font-size: 13px;
        color: var(--slate);
        margin-bottom: 20px;
    }

    /* ── Pagination ── */
    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: center;
    }

    .pagination-wrap .pagination .page-link {
        font-size: 13px;
        font-weight: 600;
        color: var(--slate);
        border-color: var(--border);
        border-radius: 8px !important;
        margin: 0 2px;
        padding: 6px 12px;
    }

    .pagination-wrap .pagination .page-item.active .page-link {
        background: var(--navy);
        border-color: var(--navy);
        color: white;
    }

    .pagination-wrap .pagination .page-link:hover {
        background: var(--bg);
        color: var(--navy);
        border-color: var(--border);
    }

    /* ── Modal ── */
    .modal-card {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 24px 64px rgba(0,0,0,0.14);
    }

    .modal-header {
        background: var(--navy);
        color: white;
        border: none;
        padding: 22px 28px;
        position: relative;
    }

    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
    }

    .modal-title {
        font-weight: 800;
        font-size: 17px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-title-icon {
        width: 34px; height: 34px;
        background: rgba(59,130,246,0.2);
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: var(--blue-light);
    }

    .modal-body { 
        padding: 28px; 
        max-height: 65vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 16px 28px;
        border-top: 1px solid var(--border);
        background: var(--bg);
        gap: 10px;
    }

    .form-label {
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--slate);
        margin-bottom: 7px;
        display: block;
    }

    .form-control, .form-select {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--navy);
        background: var(--white);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }

    textarea.form-control { min-height: 160px; resize: vertical; }

    .select-multi {
        height: 110px;
        padding: 8px;
    }

    .select-multi option {
        padding: 5px 8px;
        border-radius: 6px;
    }

    .select-hint {
        font-size: 11.5px;
        color: var(--slate-light);
        margin-top: 5px;
    }

    .btn-cancel {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 9px 20px;
        font-weight: 700;
        font-size: 13.5px;
        color: var(--slate);
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-cancel:hover {
        border-color: var(--navy);
        color: var(--navy);
    }
</style>
@endsection

@section('content')
<div class="articles-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <span class="page-label">Article Management</span>
            <h1 class="page-title">My Articles</h1>
            <p class="page-sub">{{ $contents->total() }} article{{ $contents->total() !== 1 ? 's' : '' }} total</p>
        </div>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'author')
        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-plus-lg"></i> New Article
        </button>
        @endif
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Articles Table --}}
    <div class="articles-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $content)
                    <tr>
                        <td>
                            <span class="article-title-cell">
                                {{ Str::limit($content->title, 55) }}
                            </span>
                        </td>
                        <td>
                            <span class="category-pill">
                                <i class="bi bi-folder2" style="font-size:10px;"></i>
                                {{ $content->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            @php $status = $content->status ?? 'draft'; @endphp
                            <span class="status-badge status-{{ $status }}">
                                @if($status === 'published') ✓ Published
                                @elseif($status === 'draft') ✎ Draft
                                @else ⊘ Hidden
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="views-cell">
                                <i class="bi bi-eye"></i>
                                {{ number_format(\Cache::get('content_views_total_' . $content->id, 0)) }}
                            </span>
                        </td>
                        <td>
                            <span class="date-cell">
                                {{ $content->created_at->format('M d, Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('contents.show', $content) }}"
                                   class="action-btn btn-view" title="View article">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->can('update', $content))
                                <a href="{{ route('contents.edit', $content) }}"
                                   class="action-btn btn-edit" title="Edit article">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @if(Auth::user()->can('delete', $content))
                                <form action="{{ route('contents.destroy', $content) }}"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this article?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Delete article">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon-wrap">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h5>No articles yet</h5>
                                <p>Start writing and publish your first article.</p>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'author')
                                <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                    <i class="bi bi-plus-lg"></i> Create First Article
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contents->hasPages())
        <div class="pagination-wrap">
            {{ $contents->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ── CREATE ARTICLE MODAL ── --}}
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'author')
<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-card">

            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    Create New Article
                </div>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('contents.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-4">

                        {{-- Title --}}
                        <div class="col-12">
                            <label class="form-label">Article Title</label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Enter a compelling title…"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category + Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                                 @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Publish Status</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                <option value="draft"     @selected(old('status') === 'draft')>✎ Draft</option>
                                <option value="published" @selected(old('status') === 'published')>✓ Published</option>
                                <option value="hidden"    @selected(old('status') === 'hidden')>⊘ Hidden</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="col-12">
                            <label class="form-label">Article Content</label>
                            <textarea name="body"
                                      class="form-control @error('body') is-invalid @enderror"
                                      placeholder="Write your article here…"
                                      required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tags --}}
                        <div class="col-12">
                            <label class="form-label">Tags</label>
                            <select name="tags[]" class="form-select select-multi" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        @selected(is_array(old('tags')) && in_array($tag->id, old('tags')))>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="select-hint">Hold Ctrl / ⌘ to select multiple tags</p>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn-create">
                        <i class="bi bi-send-fill"></i> Publish Article
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endif

@endsection