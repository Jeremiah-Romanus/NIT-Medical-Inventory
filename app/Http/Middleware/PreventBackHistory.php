<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Prevent browser back/forward cache from exposing protected or stale pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get') && $request->routeIs('login') && Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        $response->headers->set('Surrogate-Control', 'no-store');

        return $response;
    }
}
