<?php

namespace App\Providers;

use App\Repositories\Contracts\LandingPageRepositoryInterface;
use App\Repositories\Eloquent\LandingPageRepository;
use Illuminate\Support\ServiceProvider;

class LandingPageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            LandingPageRepositoryInterface::class,
            LandingPageRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
