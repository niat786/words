@props([
    'sidebar' => false,
])

@php
    $brandName = $globalSiteName ?? config('app.name');
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden {{ empty($globalSiteLogoUrl) ? 'bg-accent-content text-accent-foreground' : '' }}">
            @if (! empty($globalSiteLogoUrl))
                <img src="{{ $globalSiteLogoUrl }}" alt="Site logo" class="size-full object-cover">
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden {{ empty($globalSiteLogoUrl) ? 'bg-accent-content text-accent-foreground' : '' }}">
            @if (! empty($globalSiteLogoUrl))
                <img src="{{ $globalSiteLogoUrl }}" alt="Site logo" class="size-full object-cover">
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
