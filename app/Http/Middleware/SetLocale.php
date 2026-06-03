<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check session for locale, fallback to app locale
        $locale = session('locale', config('app.locale'));

        // Only allow 'en' or 'sw' as valid locales
        if (! in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}