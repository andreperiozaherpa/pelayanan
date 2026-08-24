<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $login = $request->input('login', $request->input('username'));

        $user = User::where('email', $login)->orWhere('name', $login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            if ($user) {
                Audit::log('login_failed', $user, [
                    'ip' => $request->ip(),
                    'ua' => $request->userAgent(),
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Invalid credentials',
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Account has been deactivated.',
            ], 403);
        }

        if ($request->filled('role') && (! $user->role || $user->role->slug !== $request->role)) {
            return response()->json([
                'success' => false,
                'error' => 'Role mismatch.',
            ], 403);
        }

        Auth::login($user);

        $tokenName = 'auth_token_'.md5($request->ip().$request->userAgent());
        $accessToken = $user->createToken($tokenName, ['*'], now()->addHours(8))->plainTextToken;
        $refreshName = 'refresh_'.$tokenName;
        $refreshToken = $user->createToken($refreshName, ['*'], now()->addDays(30))->plainTextToken;

        Audit::log('login_success', $user, [
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user' => new UserResource($user),
            ],
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $request->validate(['refresh_token' => ['required', 'string']]);

        $tokenId = explode('|', $request->refresh_token)[0] ?? null;
        $token = $tokenId ? PersonalAccessToken::find($tokenId) : null;

        if (! $token || ! str_starts_with($token->name, 'refresh_') || ($token->expires_at && $token->expires_at->isPast())) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid or expired refresh token',
            ], 401);
        }

        $user = $token->tokenable;
        $token->delete();

        $tokenName = 'auth_token_'.md5($request->ip().$request->userAgent());
        $accessToken = $user->createToken($tokenName, ['*'], now()->addHours(8))->plainTextToken;
        $refreshName = 'refresh_'.$tokenName;
        $refreshToken = $user->createToken($refreshName, ['*'], now()->addDays(30))->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        Audit::log('logout', $user);

        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }
}
