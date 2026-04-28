<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Create a new audit log entry.
     *
     * @param  mixed  $target  Can be a Model instance, a table name string, or null
     */
    public function log(string $action, mixed $target = null, ?array $newValue = null, ?array $oldValue = null): void
    {
        // Capture the user ID now, as it might not be available inside the deferred callback
        $userId = Auth::id();

        // Use Laravel 11+'s defer() to handle the database write after the response is sent
        defer(fn () => AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'target_table' => is_object($target) && method_exists($target, 'getTable') ? $target->getTable() : (is_string($target) ? $target : null),
            'target_id' => is_object($target) && method_exists($target, 'getKey') ? $target->getKey() : (is_numeric($target) ? $target : null),
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'timestamp' => now(),
        ]));
    }
}
