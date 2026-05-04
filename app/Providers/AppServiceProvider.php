<?php

namespace App\Providers;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('audit', function ($app) {
            return new AuditService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global Gate Bridge (RBAC)
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
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
