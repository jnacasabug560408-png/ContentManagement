<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });

        Gate::define('canCreateContent', function (User $user) {
            return in_array($user->role, ['admin', 'editor', 'author']);
        });

        Gate::define('moderate.comments', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });

        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}