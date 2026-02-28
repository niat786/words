<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<section class="mt-2">
    <div class="text-center mb-12">
        <div
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500/10 text-green-600 dark:text-green-400 text-[11px] font-bold uppercase tracking-wider mb-4">
            <i class="fa-solid fa-gamepad"></i>
            More Puzzles
        </div>
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 dark:text-white mb-4">
            Play Other Games
        </h2>
        <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto">
            Explore our collection of mind-bending word puzzles and brain teasers
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Game Card 1: Connections -->
        <a href="#"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-purple-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 bg-linear-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fa-solid fa-puzzle-piece text-xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-purple-500/10 text-purple-600 dark:text-purple-400 text-xs font-bold rounded-full">Popular</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Connections</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Find groups of four items that share something in common. Categories can be tricky!
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            5-10 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Medium
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-purple-600 dark:text-purple-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Game Card 2: Spelling Bee -->
        <a href="{{ route('spell-bee') }}"
            data-game-track="spellbee"
            data-game-track-event="open_game"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-amber-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 text-3xl bg-linear-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        🐝
                    </div>
                    <span
                        class="px-3 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full">Daily</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Spelling Bee</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Create words using 7 letters in a honeycomb. How many can you find? Aim for Queen Bee!
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            10-20 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Easy
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-amber-600 dark:text-amber-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Game Card 3: Crossword -->
        <a href="#"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-blue-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 bg-linear-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fa-solid fa-table-cells text-xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full">Classic</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Mini Crossword</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Quick daily crossword puzzle. Perfect for your coffee break. New puzzle every day!
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            3-5 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Easy
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Game Card 4: Letter Boxed -->
        <a href="#"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-emerald-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 bg-linear-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fa-solid fa-square text-xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-full">Challenge</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Letter Boxed</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Connect letters around the box to form words. Use all letters with fewest words possible.
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            8-15 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Hard
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Game Card 5: Tiles -->
        <a href="#"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-rose-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-rose-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 bg-linear-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fa-solid fa-grip text-xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-full">New</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Tiles</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Match tiles strategically to clear the board. Plan your moves carefully to win!
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            5-8 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Medium
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-rose-600 dark:text-rose-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        <!-- Game Card 6: Anagrams -->
        <a href="#"
            class="group relative overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-slate-200/60 dark:border-white/5 hover:border-indigo-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-2">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:blur-2xl transition-all">
            </div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-14 h-14 bg-linear-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <i class="fa-solid fa-shuffle text-xl"></i>
                    </div>
                    <span
                        class="px-3 py-1 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-full">Fun</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Anagrams</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                    Unscramble letters to form words against the clock. Race to beat your best time!
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i>
                            2-5 min
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-signal"></i>
                            Easy
                        </span>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-indigo-600 dark:text-indigo-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- View All Games Button -->
    {{-- <div class="text-center mt-12">
        <a href="#"
            class="inline-flex items-center gap-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-4 rounded-2xl font-bold text-base hover:scale-105 transition-all shadow-xl hover:shadow-2xl active:scale-95">
            View All Games
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div> --}}
</section>
