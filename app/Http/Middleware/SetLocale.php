<?php

namespace App\Http\Middleware;

use App\Support\Localization\SupportedLocales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
        $routeLocale = $request->route('locale');
        $localizedRouteNames = ['home', 'wordle', 'spell-bee', 'blog.index', 'blog.show'];

        if (is_string($routeLocale)) {
            $resolvedLocale = SupportedLocales::fromUrlSegment($routeLocale);
            $locale = $resolvedLocale ?? SupportedLocales::defaultLocale();
            $request->session()->put('locale', $locale);
        } else {
            $sessionLocale = $request->session()->get('locale');
            $locale = is_string($sessionLocale)
                ? SupportedLocales::normalize($sessionLocale)
                : SupportedLocales::defaultLocale();
        }

        $routeName = $request->route()?->getName();

        if (
            ! is_string($routeLocale)
            && is_string($routeName)
            && in_array($routeName, $localizedRouteNames, true)
            && ! SupportedLocales::isDefault($locale)
        ) {
            $prefix = '/'.SupportedLocales::toUrlSegment($locale);
            $requestUri = $request->getRequestUri();

            return redirect($prefix.($requestUri === '/' ? '' : $requestUri));
        }

        app()->setLocale($locale);
        URL::defaults([
            'locale' => SupportedLocales::isDefault($locale) ? null : SupportedLocales::toUrlSegment($locale),
        ]);

        return $next($request);
    }
}
