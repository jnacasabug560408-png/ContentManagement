<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Content;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // ── ADMIN GATE ──
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // ── MODERATE COMMENTS GATE ──
        Gate::define('moderate.comments', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });

        // ── CREATE CONTENT GATE ──
        Gate::define('canCreateContent', function (User $user) {
            return $user->canCreateContent();
        });

        // ── EDIT CONTENT GATE ──
        Gate::define('edit.content', function (User $user, Content $content) {
            return $user->isAdmin() || $content->user_id === $user->id;
        });

        // ── DELETE CONTENT GATE ──
        Gate::define('delete.content', function (User $user, Content $content) {
            return $user->isAdmin() || $content->user_id === $user->id;
        });
    }
}