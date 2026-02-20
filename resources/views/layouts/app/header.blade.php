<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />
            <flux:spacer />
            <a
                href="{{ url('/admin') }}"
                class="inline-flex items-center rounded-lg border border-zinc-300 px-3 py-1.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
            >
                {{ __('Back to Admin') }}
            </a>
        </flux:header>

        {{ $slot }}

        @include('partials.game-analytics-client', ['analyticsContext' => 'app'])

        @fluxScripts
    </body>
</html>
