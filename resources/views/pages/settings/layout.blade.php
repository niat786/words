<div class="mx-auto w-full max-w-7xl">
    <div class="grid gap-6 lg:grid-cols-12">
        <aside class="lg:col-span-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3 uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                    {{ __('Settings') }}
                </flux:heading>

                <flux:navlist aria-label="{{ __('Settings') }}">
                    <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
                    <flux:navlist.item :href="route('user-password.edit')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Two-Factor Auth') }}</flux:navlist.item>
                    @endif
                    <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
                </flux:navlist>
            </div>
        </aside>

        <div class="lg:col-span-9">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading class="text-zinc-900 dark:text-zinc-100">{{ $heading ?? '' }}</flux:heading>
                <flux:subheading class="mt-1 text-zinc-600 dark:text-zinc-400">{{ $subheading ?? '' }}</flux:subheading>

                <div class="mt-6 w-full max-w-3xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
