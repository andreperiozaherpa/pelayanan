<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check() || !Auth::user()->hasPermission($permission)) {
            abort(403, "Akses ditolak. Anda tidak memiliki izin ({$permission}) untuk mengakses halaman ini.");
        }

        return $next($request);
    }
}
