<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! Auth::check() || ! Auth::user()->hasPermission($permission)) {
            abort(403, "Akses ditolak. Anda tidak memiliki izin ({$permission}) untuk mengakses halaman ini.");
        }

        return $next($request);
    }
}
