<?php

namespace App\Providers;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\AuditService;
use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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

        View::composer('layouts.partials.sidebar', function ($view) {
            $user = Auth::user();
            if ($user && $user->can('service.manage')) {
                $query = ServiceRequest::where('status', 'PENDING');

                if ($user->desa_id) {
                    $query->whereHas('citizen', function ($q) use ($user) {
                        $q->where('desa_id', $user->desa_id);
                    });
                }

                $pendingRequests = $query->get();

                $badges = [
                    'all' => $pendingRequests->count(),
                    'KETERANGAN KEMISKINAN' => $pendingRequests->where('service_type', 'KETERANGAN KEMISKINAN')->count(),
                    'PENGANTAR PINDAH' => $pendingRequests->where('service_type', 'PENGANTAR PINDAH')->count(),
                    'KETERANGAN DOMISILI' => $pendingRequests->where('service_type', 'KETERANGAN DOMISILI')->count(),
                    'SURAT KEMATIAN' => $pendingRequests->where('service_type', 'SURAT KEMATIAN')->count(),
                ];

                $view->with('pendingBadges', $badges);
            }
        });
    }
}
