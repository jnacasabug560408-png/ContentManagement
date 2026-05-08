@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Manage Categories')

@section('styles')
<style>
    :root {
        --navy: #0f172a;
        --blue: #3b82f6;
        --slate: #64748b;
        --slate-light: #94a3b8;
        --border: #e2e8f0;
        --white: #ffffff;
        --bg: #f8fafc;
    }

    .categories-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 0 60px;
    }

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

    .page-sub { font-size: 13px; color: var(--slate); margin: 0; }

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
        white-space: nowrap;
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
    }

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

    .alert-error-custom {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: #be123c;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .categories-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }

    .categories-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #10b981, #3b82f6);
        border-radius: 18px 18px 0 0;
    }

    /* ── Fixed table layout to eliminate excess column space ── */
    .table {
        margin: 0;
        table-layout: fixed;
        width: 100%;
    }

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

    /* Column widths */
    .table thead th:nth-child(1) { width: 65%; }
    .table thead th:nth-child(2) { width: 35%; }
    .table thead th:nth-child(3) { width: 20%; }

    .table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        border-color: var(--border);
        font-size: 13.5px;
    }

    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: var(--bg); }

    .cat-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cat-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--navy);
    }

    .posts-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 13px;
        background: #eff6ff;
        color: #2563eb;
    }

    .posts-count.has-posts {
        background: #dcfce7;
        color: #15803d;
    }

    .action-group { display: flex; gap: 5px; align-items: center; }

    .action-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
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
    .btn-delete:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }

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

    .empty-state h5 { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; color: var(--slate); }

    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="categories-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <span class="page-label">Administration</span>
            <h1 class="page-title">Categories</h1>
            <p class="page-sub">{{ $categories->total() }} categor{{ $categories->total() !== 1 ? 'ies' : 'y' }} total</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Add Category
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error-custom">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="categories-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Posts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    @php
                        $slug = strtolower($category->slug);
                        $cc = $catColors[$slug] ?? ['bg'=>'#f5f3ff','color'=>'#7c3aed','icon'=>'bi-folder'];
                    @endphp
                    <tr>
                        <td>
                            <div class="cat-name-cell">
                                <span class="cat-name">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="posts-count {{ $category->contents_count > 0 ? 'has-posts' : '' }}">
                                {{ $category->contents_count }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('categories.show', $category) }}"
                                   class="action-btn btn-view" title="View posts">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="action-btn btn-edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="action-btn btn-delete" title="Delete"
                                            {{ $category->contents_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <div class="empty-icon-wrap">
                                    <i class="bi bi-folder-x"></i>
                                </div>
                                <h5>No categories yet</h5>
                                <p>Create your first category to organize content.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        
    </div>

</div>
@endsection