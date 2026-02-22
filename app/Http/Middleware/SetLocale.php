<?php

namespace App\Http\Middleware;

use App\Support\Localization\SupportedLocales;
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
        $sessionLocale = $request->session()->get('locale');
        $locale = is_string($sessionLocale)
            ? SupportedLocales::normalize($sessionLocale)
            : SupportedLocales::defaultLocale();

        app()->setLocale($locale);

        return $next($request);
    }
}
