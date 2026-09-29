<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class ApiSessionService
{
    public const ACCESS_ABILITY = 'api:access';

    public const REFRESH_ABILITY = 'auth:refresh';

    private const ACCESS_PREFIX = 'api-access:';

    private const REFRESH_PREFIX = 'api-refresh:';

    /** @return array{access_token: string, refresh_token: string} */
    public function create(User $user): array
    {
        return DB::transaction(function () use ($user) {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $this->ensureActive($user);

            return $this->issuePair($user, (string) Str::uuid());
        });
    }

    /** @return array{access_token: string, refresh_token: string} */
    public function refresh(string $plainTextToken): array
    {
        $candidate = PersonalAccessToken::findToken($plainTextToken);

        if (! $candidate || ! $this->isRefreshToken($candidate) || ! $candidate->tokenable instanceof User) {
            throw new AuthenticationException('Invalid or expired refresh token');
        }

        return DB::transaction(function () use ($candidate) {
            $user = User::query()->lockForUpdate()->find($candidate->tokenable_id);

            if (! $user) {
                throw new AuthenticationException('Invalid or expired refresh token');
            }

            $this->ensureActive($user);
            $token = $user->tokens()->whereKey($candidate->id)->lockForUpdate()->first();

            if (! $token || ! $this->isRefreshToken($token) || ! hash_equals($candidate->token, $token->token)) {
                throw new AuthenticationException('Invalid or expired refresh token');
            }

            $sessionId = substr($token->name, strlen(self::REFRESH_PREFIX));
            $this->deletePair($user, $sessionId);

            return $this->issuePair($user, $sessionId);
        });
    }

    public function revoke(User $user, bool $all = false): void
    {
        $token = $user->currentAccessToken();

        if (! $token instanceof PersonalAccessToken || ! self::isAccessToken($token)) {
            throw new AuthenticationException('An access token is required');
        }

        DB::transaction(function () use ($user, $token, $all) {
            $owner = User::query()->lockForUpdate()->findOrFail($user->id);

            if ($all) {
                $owner->tokens()->delete();

                return;
            }

            // The session ID survives rotation, including a refresh racing with logout.
            $this->deletePair($owner, substr($token->name, strlen(self::ACCESS_PREFIX)));
        });
    }

    public static function isAccessToken(PersonalAccessToken $token): bool
    {
        return str_starts_with($token->name, self::ACCESS_PREFIX)
            && Str::isUuid(substr($token->name, strlen(self::ACCESS_PREFIX)))
            && $token->abilities === [self::ACCESS_ABILITY];
    }

    private function isRefreshToken(PersonalAccessToken $token): bool
    {
        return str_starts_with($token->name, self::REFRESH_PREFIX)
            && Str::isUuid(substr($token->name, strlen(self::REFRESH_PREFIX)))
            && $token->abilities === [self::REFRESH_ABILITY]
            && $token->expires_at !== null
            && $token->expires_at->isFuture();
    }

    private function ensureActive(User $user): void
    {
        if (! $user->is_active) {
            abort(response()->json(['success' => false, 'error' => 'Account has been deactivated.'], 403));
        }
    }

    /** @return array{access_token: string, refresh_token: string} */
    private function issuePair(User $user, string $sessionId): array
    {
        return [
            'access_token' => $user->createToken(self::ACCESS_PREFIX.$sessionId, [self::ACCESS_ABILITY], now()->addHours(8))->plainTextToken,
            'refresh_token' => $user->createToken(self::REFRESH_PREFIX.$sessionId, [self::REFRESH_ABILITY], now()->addDays(30))->plainTextToken,
        ];
    }

    private function deletePair(User $user, string $sessionId): void
    {
        $user->tokens()->whereIn('name', [self::ACCESS_PREFIX.$sessionId, self::REFRESH_PREFIX.$sessionId])->delete();
    }
}
