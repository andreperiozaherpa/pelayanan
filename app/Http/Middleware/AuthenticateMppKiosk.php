<?php

namespace App\Http\Middleware;

use App\Models\MppKioskDevice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMppKiosk
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if ($token === '') {
            return response()->json(['success' => false, 'error' => 'Token kiosk diperlukan.'], 401);
        }

        $device = MppKioskDevice::query()
            ->where('token_hash', hash('sha256', $token))
            ->where('is_active', true)
            ->first();

        if (! $device) {
            return response()->json(['success' => false, 'error' => 'Token kiosk tidak valid atau telah dicabut.'], 401);
        }

        $device->forceFill(['last_seen_at' => now()])->save();
        $request->attributes->set('mpp_kiosk_device', $device);

        return $next($request);
    }
}
