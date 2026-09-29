<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\ApiSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private readonly ApiSessionService $sessions) {}

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

        $tokens = $this->sessions->create($user);

        Audit::log('login_success', $user, [
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
        ], actor: $user);

        return response()->json([
            'success' => true,
            'data' => [
                ...$tokens,
                'user' => new UserResource($user),
            ],
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $validated = $request->validate(['refresh_token' => ['required', 'string', 'max:512']]);

        return response()->json([
            'success' => true,
            'data' => $this->sessions->refresh($validated['refresh_token']),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $this->sessions->revoke($user);
        Audit::log('logout', $user);

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->sessions->revoke($user, all: true);
        Audit::log('logout_all', $user);

        return response()->json(['success' => true, 'message' => 'Successfully logged out from all devices']);
    }
}
