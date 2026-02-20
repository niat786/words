<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-gradient-to-br from-white via-zinc-50 to-emerald-50 p-6 shadow-xl shadow-emerald-100/30 dark:border-zinc-700 dark:from-zinc-900 dark:via-zinc-900 dark:to-emerald-950/30 dark:shadow-none">
            <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-emerald-300/25 blur-3xl dark:bg-emerald-500/10"></div>
            <div class="absolute -bottom-20 -left-20 h-52 w-52 rounded-full bg-cyan-300/20 blur-3xl dark:bg-cyan-500/10"></div>

            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Control Center</p>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">Welcome back, {{ auth()->user()->name }}.</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-zinc-900 dark:text-zinc-100">Welcome to your dashboard</h1>
                
            </div>
        </section>
    </div>
</x-layouts::app>
