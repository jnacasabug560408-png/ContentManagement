<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsController;
use App\Models\User;
use App\Models\Content;

// ── DEFINE GATES AT THE START ──
Gate::define('admin', function (User $user) {
    return $user->isAdmin();
});

Gate::define('moderate.comments', function (User $user) {
    return in_array($user->role, ['admin', 'editor']);
});

Gate::define('canCreateContent', function (User $user) {
    return $user->canCreateContent();
});

Gate::define('edit.content', function (User $user, Content $content) {
    return $user->isAdmin() || $content->user_id === $user->id;
});

Gate::define('delete.content', function (User $user, Content $content) {
    return $user->isAdmin() || $content->user_id === $user->id;
});

// 1. LANDING PAGE & PUBLIC VIEWING
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/posts', [ContentController::class, 'publicIndex'])->name('posts.public.index');
Route::get('/posts/{content:slug}', [ContentController::class, 'show'])->name('posts.public.show');
Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');
Route::get('/category-filter/{category:slug}', [CategoryController::class, 'show'])->name('categories.filter');

// 2. GUEST ROUTES
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// 3. PROTECTED ROUTES
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── COMMENTS ──
    Route::post('/posts/{content:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/my-comments', [CommentController::class, 'subscriberIndex'])->name('subscriber.comments');
    Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ── NEWS ──
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::post('/news/{news}/like', [NewsController::class, 'like'])->name('news.like');
    Route::post('/news/{news}/comment', [NewsController::class, 'comment'])->name('news.comment');
    Route::delete('/news/comments/{comment}', [NewsController::class, 'deleteComment'])->name('news.comment.delete');
    Route::get('/my-news', [NewsController::class, 'myNews'])->name('news.my');
    Route::get('/my-news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/my-news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/my-news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/my-news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/my-news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    // ── CONTENT CREATION ── (Available to all authenticated users)
    Route::resource('contents', ContentController::class);
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);

    // ── MODERATION ──
    Route::middleware('can:moderate.comments')->group(function () {
        Route::get('/all-comments', [CommentController::class, 'index'])->name('comments.index');
        Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::patch('/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
        Route::patch('/comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam');
        Route::patch('/contents/{content}/publish', [ContentController::class, 'publish'])->name('contents.publish');
        Route::get('/contents-moderation', [ContentController::class, 'moderation'])->name('contents.moderation');
    });

    // ── ADMIN ONLY ──
    Route::middleware('can:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::resource('categories', CategoryController::class);
        Route::resource('tags', TagController::class)->except(['show']);
        Route::patch('/contents/{content}/status', [ContentController::class, 'updateStatus'])->name('contents.updateStatus');
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    });

});