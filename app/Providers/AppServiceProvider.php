<?php

namespace App\Providers;

use App\Models\CmsArticle;
use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsPortfolio;
use App\Models\CmsService;
use App\Models\CmsSetting;
use App\Models\CmsStatistic;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\CmsWhyChooseUs;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Observers\LandingPageCacheObserver;
use App\Services\ApiSessionService;
use App\Services\AuditService;
use Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        Sanctum::authenticateAccessTokensUsing(function (PersonalAccessToken $token, bool $isValid): bool {
            return $isValid && ApiSessionService::isAccessToken($token);
        });

        $throttled = fn (Request $request, array $headers) => response()->json([
            'success' => false,
            'error' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.',
        ], 429, $headers);

        RateLimiter::for('api-login', function (Request $request) use ($throttled): array {
            $login = $request->input('login', $request->input('username', ''));
            $identity = is_string($login) ? mb_strtolower(trim($login)) : '';

            return [
                Limit::perMinute(5)->by('login:'.hash('sha256', $identity).'|'.$request->ip())->response($throttled),
                Limit::perMinute(60)->by('login-ip:'.$request->ip())->response($throttled),
            ];
        });
        RateLimiter::for('api-refresh', fn (Request $request) => Limit::perMinute(60)->by('refresh-ip:'.$request->ip())->response($throttled));

        if ($this->app->environment('production') || config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register landing page cache invalidation observers
        $cmsModels = [
            CmsArticle::class, CmsBanner::class, CmsFaq::class, CmsPage::class,
            CmsMenu::class,
            CmsPortfolio::class, CmsService::class, CmsSetting::class,
            CmsStatistic::class, CmsTeam::class, CmsTestimonial::class,
            CmsWebsiteSection::class, CmsWhyChooseUs::class,
        ];

        foreach ($cmsModels as $model) {
            $model::observe(LandingPageCacheObserver::class);
        }

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
