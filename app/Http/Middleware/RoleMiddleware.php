<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles)) {
            // Redirect sesuai dengan rolenya
            switch ($user->role ?? 'default') {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'bendahara':
                    return redirect()->route('bendahara.dashboard');
                case 'pengasuh':
                    return redirect()->route('pengasuh.dashboard');
                default:
                    return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
