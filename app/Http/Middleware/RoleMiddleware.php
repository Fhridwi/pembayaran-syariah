<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    /** @var \Illuminate\Contracts\Auth\Guard $auth */
    $auth = auth();

    if (!$auth->check() || !in_array($auth->user()->role, $roles)) {
        abort(403, 'Akses ditolak.');
    }

    return $next($request);
}

}
