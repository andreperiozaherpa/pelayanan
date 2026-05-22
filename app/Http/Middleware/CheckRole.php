<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check() || ! Auth::user()->role || Auth::user()->role->slug !== $role) {
            abort(403, 'Akses ditolak. Anda tidak memiliki akses untuk halaman ini.');
        }

        return $next($request);
    }
}
