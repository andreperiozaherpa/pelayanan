<?php

namespace App\Facades;

use App\Services\AuditService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void log(string $action, mixed $target = null, ?array $newValue = null, ?array $oldValue = null)
 *
 * @see AuditService
 */
class Audit extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'audit';
    }
}
