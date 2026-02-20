<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<section class="mt-24">
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
                        class="w-14 h-14 bg-linear-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C10.897 2 10 2.897 10 4C10 4.552 10.224 5.053 10.586 5.414L9.293 6.707C8.902 7.098 8.902 7.731 9.293 8.121L10.879 9.707C11.269 10.098 11.902 10.098 12.293 9.707L15.707 6.293C16.098 5.902 16.098 5.269 15.707 4.879L14.121 3.293C13.731 2.902 13.098 2.902 12.707 3.293L11.414 4.586C11.053 4.224 10.552 4 10 4C8.897 4 8 4.897 8 6C8 7.103 8.897 8 10 8H14C15.103 8 16 7.103 16 6C16 4.897 15.103 4 14 4C13.448 4 12.947 4.224 12.586 4.586L13.879 3.293C14.269 2.902 14.902 2.902 15.293 3.293L16.879 4.879C17.269 5.269 17.269 5.902 16.879 6.293L13.465 9.707C13.074 10.098 12.441 10.098 12.051 9.707L10.465 8.121C10.074 7.731 10.074 7.098 10.465 6.707L11.758 5.414C11.396 5.053 11.172 4.552 11.172 4C11.172 3.448 11.396 2.947 11.758 2.586C11.052 2.214 10.552 1.552 10.552 0.793C10.552 0.355 10.908 0 11.345 0C11.782 0 12.138 0.355 12.138 0.793C12.138 1.231 11.782 1.586 11.345 1.586C10.908 1.586 10.552 1.931 10.552 2.369C10.552 2.807 10.908 3.162 11.345 3.162C11.782 3.162 12.138 3.517 12.138 3.955C12.138 4.393 11.782 4.748 11.345 4.748C10.908 4.748 10.552 5.103 10.552 5.541C10.552 5.979 10.908 6.334 11.345 6.334C11.782 6.334 12.138 6.689 12.138 7.127C12.138 7.565 11.782 7.92 11.345 7.92H10C9.448 7.92 9 7.472 9 6.92C9 6.368 9.448 5.92 10 5.92C10.552 5.92 11 5.472 11 4.92C11 4.368 10.552 3.92 10 3.92Z">
                            </path>
                            <path
                                d="M12 9C9.243 9 7 11.243 7 14V16C7 17.657 8.343 19 10 19H14C15.657 19 17 17.657 17 16V14C17 11.243 14.757 9 12 9ZM12 11C13.654 11 15 12.346 15 14V16C15 16.552 14.552 17 14 17H10C9.448 17 9 16.552 9 16V14C9 12.346 10.346 11 12 11Z">
                            </path>
                            <ellipse cx="12" cy="15" rx="1.5" ry="1" fill="currentColor">
                            </ellipse>
                            <path
                                d="M5 14C5 12.343 6.343 11 8 11V9C5.239 9 3 11.239 3 14V16C3 18.761 5.239 21 8 21H16C18.761 21 21 18.761 21 16V14C21 11.239 18.761 9 16 9V11C17.657 11 19 12.343 19 14V16C19 17.657 17.657 19 16 19H8C6.343 19 5 17.657 5 16V14Z">
                            </path>
                        </svg>
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
    <div class="text-center mt-12">
        <a href="#"
            class="inline-flex items-center gap-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-4 rounded-2xl font-bold text-base hover:scale-105 transition-all shadow-xl hover:shadow-2xl active:scale-95">
            View All Games
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>
