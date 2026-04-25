<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Authenticate user and issue token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            // Log failed attempt (Audit)
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->recordAuditLog('login_failed', 'users', $user->id, null, [
                    'ip' => $request->ip(),
                    'ua' => $request->userAgent()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // Token name includes IP and UA for binding awareness
        $tokenName = 'auth_token_' . md5($request->ip() . $request->userAgent());

        // Access Token: 15 minutes (Blueprint 3.2)
        $token = $user->createToken($tokenName, ['*'], now()->addMinutes(15))->plainTextToken;

        // Log successful login (Audit)
        $user->recordAuditLog('login_success', 'users', $user->id, null, [
            'ip' => $request->ip(),
            'ua' => $request->userAgent()
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user)
            ]
        ]);
    }

    /**
     * Refresh access token (Rotation logic).
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke current token
        $user->currentAccessToken()->delete();

        // Issue new access token (15 mins)
        $tokenName = 'auth_token_' . md5($request->ip() . $request->userAgent());
        $token = $user->createToken($tokenName, ['*'], now()->addMinutes(15))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * Revoke access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Log logout (Audit)
        $user->recordAuditLog('logout', 'users', $user->id);

        // Revoke current token
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }
}
