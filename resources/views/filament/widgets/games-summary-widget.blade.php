<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center justify-between gap-4">
            <div class="fi-account-widget-main flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-200/70 dark:bg-primary-500/10 dark:text-primary-400 dark:ring-primary-400/20">
                    <x-filament::icon icon="heroicon-o-puzzle-piece" class="h-5 w-5" />
                </span>

                <h2 class="fi-account-widget-heading !mb-0">
                    Games
                </h2>
            </div>

            <p class="text-3xl font-black tracking-tight text-gray-950 dark:text-white">
                {{ number_format($this->getTotalGames()) }}
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
