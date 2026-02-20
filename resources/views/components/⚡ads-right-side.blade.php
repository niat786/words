<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<aside class="hidden lg:block lg:col-span-3 space-y-8">
                <!-- Ad Banner 3 -->
                <div class="sticky top-24 space-y-6">
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-red-500 p-6 shadow-lg">
                        <div class="absolute top-0 left-0 w-40 h-40 bg-white/10 rounded-full -translate-y-20 -translate-x-20"></div>
                        <div class="relative z-10">
                            <span class="inline-block px-2 py-1 bg-white/20 text-white text-[9px] font-bold uppercase tracking-wider rounded mb-3">Sponsored</span>
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-graduation-cap text-2xl text-white"></i>
                                <h4 class="text-white font-black text-lg">Word Master</h4>
                            </div>
                            <p class="text-white/90 text-xs mb-4 leading-relaxed">Learn 1000 new words in 30 days with AI-powered learning</p>
                            <button class="w-full bg-white text-orange-600 font-bold text-sm py-2.5 rounded-xl hover:bg-orange-50 transition-all active:scale-95 shadow-lg">
                                Start Learning
                            </button>
                        </div>
                    </div>

                    <!-- Ad Banner 4 -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 shadow-lg">
                        <div class="absolute inset-0">
                            <div class="absolute top-0 right-0 w-24 h-24 border border-white/20 rounded-full translate-x-12 -translate-y-12"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 border border-white/20 rounded-full -translate-x-16 translate-y-16"></div>
                        </div>
                        <div class="relative z-10">
                            <span class="inline-block px-2 py-1 bg-white/20 text-white text-[9px] font-bold uppercase tracking-wider rounded mb-3">Ad</span>
                            <h4 class="text-white font-black text-lg mb-2">🎯 Focus Timer</h4>
                            <p class="text-white/90 text-xs mb-4 leading-relaxed">Boost productivity with Pomodoro technique</p>
                            <button class="w-full bg-white text-emerald-600 font-bold text-sm py-2.5 rounded-xl hover:bg-emerald-50 transition-all active:scale-95">
                                Download App
                            </button>
                        </div>
                    </div>

                    <!-- Ad Banner 5 -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 shadow-lg">
                        <div class="absolute bottom-0 right-0 w-full h-full opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="80" cy="20" r="30" fill="white"/>
                                <circle cx="20" cy="80" r="25" fill="white"/>
                            </svg>
                        </div>
                        <div class="relative z-10">
                            <span class="inline-block px-2 py-1 bg-white/20 text-white text-[9px] font-bold uppercase tracking-wider rounded mb-3">Partner</span>
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa-solid fa-book text-2xl text-white"></i>
                                <h4 class="text-white font-black text-lg">Kindle Deals</h4>
                            </div>
                            <p class="text-white/90 text-xs mb-4 leading-relaxed">Bestselling books up to 80% off this week</p>
                            <button class="w-full bg-white text-indigo-600 font-bold text-sm py-2.5 rounded-xl hover:bg-indigo-50 transition-all active:scale-95">
                                Browse Books
                            </button>
                        </div>
                    </div>
                </div>
            </aside>