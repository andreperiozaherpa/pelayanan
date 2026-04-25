<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global Gate Bridge (RBAC)
        Gate::before(function (User $user, string $ability) {
            // SuperAdmin bypass
            if ($user->role && $user->role->slug === 'superadmin') {
                return true;
            }

            // Standard permission check
            if ($user->hasPermission($ability)) {
                return true;
            }

            return null; // Fallback to other gates/policies
        });
    }
}
