<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $resolvedTitle = $seoTitle ?? $title ?? $globalSiteName ?? config('app.name');
    $resolvedDescription = filled($seoDescription ?? null) ? $seoDescription : null;
    $resolvedKeywords = filled($seoKeywords ?? null) ? $seoKeywords : null;
    $resolvedCanonicalUrl = filled($seoCanonicalUrl ?? null) ? $seoCanonicalUrl : null;
    $resolvedRobots = filled($seoRobots ?? null) ? $seoRobots : null;
    $resolvedOpenGraph = is_array($seoOpenGraph ?? null) ? $seoOpenGraph : [];
    $resolvedTwitter = is_array($seoTwitter ?? null) ? $seoTwitter : [];
    $resolvedJsonLd = [];

    if (isset($seoJsonLd)) {
        if (is_string($seoJsonLd)) {
            $decoded = json_decode($seoJsonLd, true);
            $resolvedJsonLd = is_array($decoded) ? [json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)] : [];
        } elseif (is_array($seoJsonLd)) {
            $jsonLdItems = array_is_list($seoJsonLd) ? $seoJsonLd : [$seoJsonLd];

            foreach ($jsonLdItems as $jsonLdItem) {
                if (is_string($jsonLdItem)) {
                    $decoded = json_decode($jsonLdItem, true);

                    if (is_array($decoded)) {
                        $resolvedJsonLd[] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                } elseif (is_array($jsonLdItem)) {
                    $resolvedJsonLd[] = json_encode($jsonLdItem, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
        }
    }
@endphp

<title>{{ $resolvedTitle }}</title>
@if ($resolvedDescription !== null)
    <meta name="description" content="{{ $resolvedDescription }}" />
@endif
@if ($resolvedKeywords !== null)
    <meta name="keywords" content="{{ $resolvedKeywords }}" />
@endif
@if ($resolvedCanonicalUrl !== null)
    <link rel="canonical" href="{{ $resolvedCanonicalUrl }}" />
@endif
@if ($resolvedRobots !== null)
    <meta name="robots" content="{{ $resolvedRobots }}" />
@endif
@if (filled($resolvedOpenGraph['title'] ?? null))
    <meta property="og:title" content="{{ $resolvedOpenGraph['title'] }}" />
@endif
@if (filled($resolvedOpenGraph['description'] ?? null))
    <meta property="og:description" content="{{ $resolvedOpenGraph['description'] }}" />
@endif
@if (filled($resolvedOpenGraph['type'] ?? null))
    <meta property="og:type" content="{{ $resolvedOpenGraph['type'] }}" />
@endif
@if (filled($resolvedOpenGraph['url'] ?? null))
    <meta property="og:url" content="{{ $resolvedOpenGraph['url'] }}" />
@endif
@if (filled($resolvedOpenGraph['image'] ?? null))
    <meta property="og:image" content="{{ $resolvedOpenGraph['image'] }}" />
@endif
@if (filled($resolvedTwitter['card'] ?? null))
    <meta name="twitter:card" content="{{ $resolvedTwitter['card'] }}" />
@endif
@if (filled($resolvedTwitter['title'] ?? null))
    <meta name="twitter:title" content="{{ $resolvedTwitter['title'] }}" />
@endif
@if (filled($resolvedTwitter['description'] ?? null))
    <meta name="twitter:description" content="{{ $resolvedTwitter['description'] }}" />
@endif
@if (filled($resolvedTwitter['image'] ?? null))
    <meta name="twitter:image" content="{{ $resolvedTwitter['image'] }}" />
@endif
@foreach ($resolvedJsonLd as $jsonLdItem)
    @if (is_string($jsonLdItem))
        <script type="application/ld+json">{!! $jsonLdItem !!}</script>
    @endif
@endforeach
@if (! empty($seoRawMarkup ?? null))
    {!! $seoRawMarkup !!}
@endif

<link rel="icon" href="{{ $globalSiteFaviconUrl ?? '/favicon.ico' }}" sizes="any">
<link rel="shortcut icon" href="{{ $globalSiteFaviconUrl ?? '/favicon.ico' }}">
<link rel="apple-touch-icon" href="{{ $globalSiteAppleTouchIconUrl ?? '/apple-touch-icon.png' }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@if (! empty($globalHeaderCode))
    {!! $globalHeaderCode !!}
@endif
