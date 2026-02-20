<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

@php
    $siteName = $globalSiteName ?? config('app.name');
    $siteTagline = $globalSiteTagline ?? 'Unlimited Word Games';
    $siteDescription = $globalSiteDescription ?? 'Play daily word challenges and train your vocabulary with fast, competitive word games.';
    $siteUrl = rtrim(config('app.url') ?: url('/'), '/');
    $siteHost = parse_url($siteUrl, PHP_URL_HOST) ?: preg_replace('#^https?://#', '', $siteUrl);

    $gameLinks = [
        ['label' => 'Wordle', 'url' => route('wordle')],
        ['label' => 'SpellBee', 'url' => route('spell-bee')],
        ['label' => 'Blog', 'url' => route('blog.index')],
   
    ];

    $siteLinks = [
        ['label' => 'Home', 'url' => $siteUrl],
        ['label' => 'Login', 'url' => route('login')],
        ['label' => 'Register', 'url' => route('register')],
    ];

    if (auth()->check()) {
        $siteLinks[] = ['label' => 'Dashboard', 'url' => url('/admin')];
    }

    $socialLinks = array_values(array_filter([
        ['label' => 'Facebook', 'url' => $globalFacebookUrl ?? null, 'icon' => 'fa-brands fa-facebook-f'],
        ['label' => 'Instagram', 'url' => $globalInstagramUrl ?? null, 'icon' => 'fa-brands fa-instagram'],
        ['label' => 'YouTube', 'url' => $globalYoutubeUrl ?? null, 'icon' => 'fa-brands fa-youtube'],
        ['label' => 'X', 'url' => $globalXUrl ?? null, 'icon' => 'fa-brands fa-x-twitter'],
        ['label' => 'Pinterest', 'url' => $globalPinterestUrl ?? null, 'icon' => 'fa-brands fa-pinterest-p'],
    ], fn (array $social): bool => filled($social['url'])));
@endphp

<footer class="relative mt-32 overflow-hidden border-t border-slate-200/60 bg-gray-50 pt-20 pb-10 transition-colors duration-300 dark:border-white/5 dark:bg-[#0c0d0e] dark:text-slate-300">
    <div class="absolute top-0 left-1/2 -z-10 h-64 w-full -translate-x-1/2 bg-gradient-to-b from-green-500/5 to-transparent opacity-50 dark:from-green-500/10"></div>

    <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-1 gap-12 mb-16 lg:grid-cols-12">
            
            <div class="space-y-6 lg:col-span-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl text-white shadow-lg transition-transform duration-500 group-hover:rotate-6 {{ empty($globalSiteLogoUrl) ? 'bg-gradient-to-br from-green-400 to-green-600 shadow-green-500/20' : 'bg-white dark:bg-white/10 shadow-slate-300/40 dark:shadow-slate-950/30' }}">
                        @if (! empty($globalSiteLogoUrl))
                            <img src="{{ $globalSiteLogoUrl }}" alt="{{ $siteName }} logo" class="h-full w-full object-cover">
                        @else
                            <i class="fa-solid fa-w text-xl font-black"></i>
                        @endif
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                    </div>
                    <h2 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white">
                        {{ $siteName }}<span class="text-green-500">.</span>
                    </h2>
                </a>
                <p class="max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    {{ $siteDescription }}
                </p>
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                    </span>
                    {{ $siteTagline }}
                </div>
            </div>

            <div class="grid grid-cols-2 lg:col-span-6">
                <div class="space-y-5">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Games</h3>
                    <ul class="space-y-3 text-sm font-semibold">
                        @foreach ($gameLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-slate-500 hover:text-green-500 transition-colors dark:text-slate-400 dark:hover:text-green-400">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
               
                <div class="space-y-5">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Site</h3>
                    <ul class="space-y-3 text-sm font-semibold">
                        @foreach ($siteLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-slate-500 hover:text-green-500 transition-colors dark:text-slate-400 dark:hover:text-green-400">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-8 border-t border-slate-200 pt-10 md:flex-row dark:border-white/5">
            <div class="text-center md:text-left">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">
                    &copy; {{ now()->year }} {{ $siteHost }} 
                </p>
                <p class="mt-1 text-[10px] text-slate-400/60">All intellectual property rights reserved.</p>
            </div>

            <div class="flex items-center gap-3">
                @foreach ($socialLinks as $social)
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm border border-slate-200 transition-all hover:-translate-y-1 hover:text-green-500 hover:border-green-500/50 dark:bg-white/5 dark:border-white/5 dark:text-slate-400">
                        <i class="{{ $social['icon'] }}"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
