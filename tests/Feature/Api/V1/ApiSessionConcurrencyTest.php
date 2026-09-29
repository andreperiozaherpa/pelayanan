<?php

use App\Models\Role;
use App\Models\User;
use App\Services\ApiSessionService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

uses(DatabaseTruncation::class);

afterEach(function () {
    $this->truncateTablesForAllConnections();
});

function concurrentRefreshTask(string $connectionName, array $connectionConfig, string $token, int $tokenId, string $barrier, int $worker): Closure
{
    return static function () use ($connectionName, $connectionConfig, $token, $tokenId, $barrier, $worker): string {
        config(['database.default' => $connectionName, 'database.connections.'.$connectionName => $connectionConfig]);
        DB::purge($connectionName);
        $ready = false;
        DB::listen(static function (QueryExecuted $query) use (&$ready, $tokenId, $barrier, $worker): void {
            if ($ready || ! str_contains($query->sql, 'personal_access_tokens') || (int) ($query->bindings[0] ?? 0) !== $tokenId) {
                return;
            }

            $ready = true;
            touch($barrier.'/'.$worker);
            $deadline = microtime(true) + 10;

            // Both workers must have read the original token before either rotates it.
            while (count(glob($barrier.'/*')) < 2) {
                if (microtime(true) > $deadline) {
                    throw new RuntimeException('Concurrent refresh barrier timed out');
                }

                usleep(10000);
            }
        });

        try {
            app(ApiSessionService::class)->refresh($token);

            return 'rotated';
        } catch (AuthenticationException) {
            return 'rejected';
        }
    };
}

test('only one process can rotate a refresh token read concurrently', function () {
    expect(DB::connection()->getDriverName())->toBe('mysql');
    Role::create(['name' => 'Superadmin', 'slug' => 'superadmin']);
    $user = User::factory()->create();
    $tokens = app(ApiSessionService::class)->create($user);
    $connectionName = config('database.default');
    $connectionConfig = config('database.connections.'.$connectionName);
    $token = $tokens['refresh_token'];
    $tokenId = (int) explode('|', $token)[0];
    $barrier = sys_get_temp_dir().'/api-refresh-test-'.Str::uuid();
    mkdir($barrier, 0700);
    $tasks = [];

    foreach ([0, 1] as $worker) {
        $tasks[] = concurrentRefreshTask($connectionName, $connectionConfig, $token, $tokenId, $barrier, $worker);
    }

    try {
        $results = Concurrency::driver('process')->run($tasks);
        sort($results);

        expect($results)->toBe(['rejected', 'rotated'])
            ->and($user->tokens()->count())->toBe(2);
    } finally {
        foreach (glob($barrier.'/*') as $file) {
            unlink($file);
        }

        rmdir($barrier);
    }
});
