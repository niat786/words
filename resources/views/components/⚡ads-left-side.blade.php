<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<aside class="hidden lg:block lg:col-span-3 space-y-6">
                <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-3xl border border-slate-100 dark:border-white/5">
                    <h3 class="text-xs font-bold text-slate-400 tracking-widest uppercase mb-4">Player Profile</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-tr from-green-400 to-emerald-600 rounded-full"></div>
                        <div>
                            <p class="font-bold text-sm">Guest_4022</p>
                            <p class="text-xs text-slate-500">Rank: Word Smith</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="p-3 bg-white dark:bg-black/20 rounded">
                            <p id="win-rate" class="text-xl font-bold">84%</p>
                            <p class="text-[10px] mt-1 text-slate-500 uppercase">Wins</p>
                        </div>
                        <div class="p-3 bg-white dark:bg-black/20 rounded">
                            <p id="streak-count" class="text-xl font-bold">05</p>
                            <p class="text-[10px] mt-1 text-slate-500 uppercase">Streak</p>
                        </div>
                    </div>
                </div>

                <!-- Ad Banner 1 -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-500 to-pink-600 p-6 shadow-lg">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
                    <div class="relative z-10">
                        <span class="inline-block px-2 py-1 bg-white/20 text-white text-[9px] font-bold uppercase tracking-wider rounded mb-3">Sponsored</span>
                        <h4 class="text-white font-black text-lg mb-2">Premium Dictionary</h4>
                        <p class="text-white/90 text-xs mb-4 leading-relaxed">Unlock 50,000+ words and advanced hints</p>
                        <button class="w-full bg-white text-purple-600 font-bold text-sm py-2.5 rounded-xl hover:bg-purple-50 transition-all active:scale-95">
                            Learn More
                        </button>
                    </div>
                </div>

                <!-- Ad Banner 2 -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-cyan-500 p-6 shadow-lg">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-4 left-4 w-20 h-20 border-2 border-white rounded-full"></div>
                        <div class="absolute bottom-4 right-4 w-16 h-16 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="relative z-10">
                        <span class="inline-block px-2 py-1 bg-white/20 text-white text-[9px] font-bold uppercase tracking-wider rounded mb-3">Ad</span>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-brain text-2xl text-white"></i>
                            <h4 class="text-white font-black text-lg">Brain Boost</h4>
                        </div>
                        <p class="text-white/90 text-xs mb-4 leading-relaxed">Train your mind with daily puzzles & challenges</p>
                        <button class="w-full bg-white text-blue-600 font-bold text-sm py-2.5 rounded-xl hover:bg-blue-50 transition-all active:scale-95">
                            Try Free
                        </button>
                    </div>
                </div>
            </aside>