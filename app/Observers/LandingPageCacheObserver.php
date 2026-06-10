<?php

namespace App\Observers;

use App\Services\LandingPageService;
use Illuminate\Database\Eloquent\Model;

class LandingPageCacheObserver
{
    public function saved(Model $model): void
    {
        LandingPageService::flushCache();
    }

    public function deleted(Model $model): void
    {
        LandingPageService::flushCache();
    }
}
