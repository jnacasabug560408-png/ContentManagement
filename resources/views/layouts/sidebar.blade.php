@php
    use Illuminate\Support\Str;

    $userRole = Auth::check() ? Str::lower(trim(Auth::user()->role)) : '';

    $isAdmin = ($userRole === 'admin');
    $isEditor = ($userRole === 'editor');
    $isSubscriber = ($userRole === 'subscriber');
    $isAuthor = ($userRole === 'author');

    $isCreatorOrHigher = in_array($userRole, ['admin', 'editor', 'author', 'creator']);
    $canModerate = in_array($userRole, ['admin', 'editor']);
@endphp

<aside class="sidebar shadow-sm" style="background: #1a1a2e; color: white; min-height: 100vh; width: 250px; padding: 20px; display: flex; flex-direction: column;">
    <h5 class="text-center py-3 border-bottom border-secondary mb-4">CMS PANEL</h5>

    <ul class="nav flex-column" style="flex: 1;">

        {{-- DASHBOARD (hidden for subscriber) --}}
        @if(!$isSubscriber)
        <li class="nav-item mb-2">
            <a class="nav-link text-white {{ request()->is('dashboard*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
               href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        @endif

        {{-- SUBSCRIBER ONLY --}}
        @if($isSubscriber)
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('articles*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('posts.public.index') }}">
                    <i class="bi bi-newspaper me-2"></i> Articles
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('news*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ url('/news') }}">
                    <i class="bi bi-broadcast me-2"></i> News
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('my-comments*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('subscriber.comments') }}">
                    <i class="bi bi-chat-left-text me-2 text-info"></i> My Comments
                </a>
            </li>
        @endif

        {{-- AUTHOR ONLY --}}
        @if($isAuthor)
            <div class="small text-uppercase text-secondary mt-3 mb-2" style="font-size: 0.7rem;">My Work</div>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('contents.index') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('contents.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> My Articles
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('news.my') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('news.my') }}">
                    <i class="bi bi-broadcast me-2"></i> My News
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('contents.create') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('contents.create') }}">
                    <i class="bi bi-pencil-square me-2"></i> Create Post
                </a>
            </li>
            <div class="small text-uppercase text-secondary mt-3 mb-2" style="font-size: 0.7rem;">Browse</div>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('articles*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('posts.public.index') }}">
                    <i class="bi bi-newspaper me-2"></i> Articles
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('news*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ url('/news') }}">
                    <i class="bi bi-broadcast me-2"></i> News
                </a>
            </li>
        @endif

        {{-- EDITOR / ADMIN --}}
        @if($isCreatorOrHigher && !$isAuthor)

            <div class="small text-uppercase text-secondary mt-3 mb-2" style="font-size: 0.7rem;">Content</div>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('contents.index') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                   href="{{ route('contents.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> My Articles
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('news.my') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                href="{{ route('news.my') }}">
                    <i class="bi bi-broadcast me-2"></i> My News
                </a>
            </li>


            {{-- ADMIN ONLY --}}
            @if($isAdmin)
                <div class="small text-uppercase text-secondary mt-3 mb-2" style="font-size: 0.7rem;">Administration</div>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('users.*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                       href="{{ route('users.index') }}">
                        <i class="bi bi-people-fill me-2"></i> Manage Users
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('categories.*') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                       href="{{ route('categories.index') }}">
                        <i class="bi bi-folder-fill me-2"></i> Categories
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('contents.moderation') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                       href="{{ route('contents.moderation') }}">
                        <i class="bi bi-shield-lock-fill me-2"></i> Moderation
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('analytics') ? 'opacity-100 fw-bold' : 'opacity-75' }}"
                       href="{{ route('analytics') }}">
                        <i class="bi bi-bar-chart-fill me-2"></i> Analytics
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-info small" href="{{ route('posts.public.index') }}">
                        <i class="bi bi-box-arrow-up-right me-2"></i> View Site
                    </a>
                </li>
            @endif

        @endif

        {{-- LOGOUT (always at bottom) --}}
        <li class="nav-item mt-auto pt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>

    </ul>
</aside>