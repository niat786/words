@props([
    'game' => null,
    'fallbackTitle' => 'Game Guide',
    'badge' => 'Game Guide',
    'topSpacing' => 'mt-16',
])

@php
    $translatedGameTitle = $game?->translated('title');
    $translatedGameDescription = $game?->translated('meta_description');
    $translatedGameContent = $game?->translated('content');
@endphp

@if (filled($translatedGameContent))
    <section class="{{ 'relative '.$topSpacing }}">
        <div class="pointer-events-none absolute -top-20 right-12 h-56 w-56 rounded-full bg-emerald-500/10 blur-3xl dark:bg-emerald-400/20"></div>
        <div class="pointer-events-none absolute -bottom-24 left-4 h-52 w-52 rounded-full bg-sky-500/10 blur-3xl dark:bg-sky-400/20"></div>

        <article class="relative overflow-hidden rounded-[2rem] border border-slate-200/70 bg-gradient-to-b from-white via-white to-slate-50 p-6 shadow-[0_30px_80px_-45px_rgba(15,23,42,0.45)] dark:border-white/10 dark:bg-gradient-to-b dark:from-[#11151c] dark:via-[#0f131a] dark:to-[#0b0f16] sm:p-8">
            <header class="relative z-10">
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.22em] text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-500/15 dark:text-emerald-300">
                    {{ $badge }}
                </div>
                <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    {{ $translatedGameTitle ?? $game?->title ?? $fallbackTitle }}
                </h2>

                @if (filled($translatedGameDescription))
                    <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600 dark:text-slate-300 sm:text-base">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) $translatedGameDescription), 220) }}
                    </p>
                @endif
            </header>

            <div class="relative z-10 mt-7 border-t border-slate-200/80 dark:border-slate-700">
                <div class="game-rich-content">
                    {!! $translatedGameContent !!}
                </div>
            </div>
        </article>
    </section>
@endif
