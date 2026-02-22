<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

@php
    $siteName = $globalSiteName ?? config('app.name');
    $siteTagline = filled($globalSiteTagline ?? null) ? $globalSiteTagline : 'Unlimited';
    $availableLocales = $globalAvailableLocales ?? [];
    $currentLocale = $globalCurrentLocale ?? app()->getLocale();
@endphp

 <nav class="sticky top-0 z-50 w-full border-b border-white/20 bg-white/70 dark:bg-slate-950/80 backdrop-blur-xl transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex h-16 items-center justify-between sm:h-20">
            
            <a href="{{ route('home') }}" class="group relative flex items-center gap-2 sm:gap-3">
                <div class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-xl shadow-lg transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 sm:h-10 sm:w-10 sm:rounded-2xl {{ empty($globalSiteLogoUrl) ? 'bg-gradient-to-br from-green-400 to-green-600 text-white shadow-green-500/30' : 'bg-white dark:bg-white/10 shadow-slate-200/70 dark:shadow-slate-950/40' }}">
                    @if (! empty($globalSiteLogoUrl))
                        <img src="{{ $globalSiteLogoUrl }}" alt="{{ $siteName }} logo" class="h-full w-full object-cover">
                    @else
                        <i class="fa-solid fa-w text-base font-black sm:text-xl"></i>
                    @endif
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-tr from-white/0 via-white/40 to-white/0 transition-transform duration-700 group-hover:translate-x-full"></div>
                </div>
                <div class="flex flex-col">
                    <h1 class="max-w-[9rem] truncate text-lg font-black leading-none tracking-tighter text-slate-900 dark:text-white sm:max-w-[12rem] sm:text-2xl">{{ $siteName }}</h1>
                    <span class="max-w-[9rem] truncate text-[8px] font-black uppercase tracking-[0.2em] text-green-500 sm:max-w-[12rem] sm:text-[10px] sm:tracking-[0.3em]">{{ $siteTagline }}</span>
                </div>
            </a>

            <div class="hidden lg:flex lg:items-center lg:gap-8">
                <a href="{{ route('home') }}" class="group relative text-sm font-bold text-slate-600 transition-colors hover:text-green-500 dark:text-slate-300">
                    Home
                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-green-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="#game-board" class="group relative text-sm font-bold text-slate-600 transition-colors hover:text-green-500 dark:text-slate-300">
                    Play
                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-green-500 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('blog.index') }}" class="group relative text-sm font-bold text-slate-600 transition-colors hover:text-green-500 dark:text-slate-300">
                    Blog
                    <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-green-500 transition-all group-hover:w-full"></span>
                </a>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <select
                    class="hidden rounded-lg border border-slate-200 bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 md:block"
                    onchange="window.location.href = '{{ url('/locale') }}/' + this.value"
                >
                    @foreach ($availableLocales as $localeCode => $localeLabel)
                        <option value="{{ $localeCode }}" @selected($currentLocale === $localeCode)>{{ $localeLabel }}</option>
                    @endforeach
                </select>

                <button id="nav-theme-toggle" onclick="toggleTheme()" class="group relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-600 transition-all active:scale-90 dark:border-white/10 dark:bg-white/5 dark:text-slate-400 sm:h-10 sm:w-10 sm:rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5 text-yellow-400 transition-transform duration-500 group-hover:rotate-90 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 transition-transform duration-500 group-hover:-rotate-12 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="hidden text-xs font-bold text-slate-600 transition-colors hover:text-green-500 dark:text-slate-300 md:inline-block">Login</a>
                    <a href="{{ route('register') }}" class="group relative overflow-hidden rounded-lg bg-slate-900 px-4 py-2 text-[10px] font-black text-white transition-all hover:-translate-y-0.5 active:translate-y-0 dark:bg-white dark:text-slate-950 sm:rounded-xl sm:px-6 sm:py-2.5 sm:text-xs">
                        <span class="relative z-10">REGISTER</span>
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-1000 group-hover:translate-x-full"></div>
                    </a>
                @else
                    <a href="{{ url('admin') }}" class="group relative overflow-hidden rounded-lg bg-green-500 px-4 py-2 text-[10px] font-black text-white shadow-lg shadow-green-500/20 transition-all hover:bg-green-600 sm:rounded-xl sm:px-6 sm:py-2.5 sm:text-xs">
                        DASHBOARD
                        <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition-transform duration-700 group-hover:translate-x-full"></div>
                    </a>
                @endguest
            </div>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-3 pt-1 no-scrollbar lg:hidden">
            <select
                class="rounded-full border border-slate-200 bg-slate-100/50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400"
                onchange="window.location.href = '{{ url('/locale') }}/' + this.value"
            >
                @foreach ($availableLocales as $localeCode => $localeLabel)
                    <option value="{{ $localeCode }}" @selected($currentLocale === $localeCode)>{{ $localeCode }}</option>
                @endforeach
            </select>
            <a href="{{ route('home') }}" class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-100/50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400">Home</a>
            <a href="#game-board" class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-100/50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400">Play</a>
            <a href="{{ route('blog.index') }}" class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-100/50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400">Blog</a>
            <a href="{{ url('/admin') }}" class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-100/50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400">Admin</a>
            @guest
                <a href="{{ route('login') }}" class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-100/50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-600 dark:border-white/5 dark:bg-white/5 dark:text-slate-400 md:hidden">Login</a>
            @endguest
        </div>
    </div>
</nav>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
