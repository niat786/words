<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @include('partials.head')

    <style>
        .spellbee-cell {
            width: 5rem;
            height: 5rem;
            clip-path: polygon(25% 5%, 75% 5%, 100% 50%, 75% 95%, 25% 95%, 0% 50%);
        }

        .spellbee-center {
            background: linear-gradient(135deg, #facc15, #f59e0b);
            color: #111827;
        }

        .spellbee-outer {
            background: #e5e7eb;
            color: #111827;
        }

        .dark .spellbee-outer {
            background: #1f2937;
            color: #f9fafb;
        }
    </style>

    <script>
        (() => {
            const applyTheme = (theme) => {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            };

            const savedTheme = localStorage.getItem('wordly-theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                applyTheme('dark');
            } else {
                applyTheme('light');
            }

            window.toggleSpellBeeTheme = () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('wordly-theme', isDark ? 'dark' : 'light');
            };
            window.toggleTheme = window.toggleSpellBeeTheme;

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
                if (localStorage.getItem('wordly-theme')) {
                    return;
                }

                applyTheme(event.matches ? 'dark' : 'light');
            });
        })();
    </script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
     @livewire('nav-bar')
 

    <main class="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.4fr_0.8fr]">
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900 transition-all">
        
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-6 dark:border-slate-800">
            <div>
                <h1 class="flex items-center gap-2 text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                    <span class="text-amber-500">🐝</span> SpellBee
                </h1>
                <p class="mt-1 text-sm font-medium text-slate-500 dark:text-slate-400">
                    Create words using <span class="text-amber-600 dark:text-amber-400 font-bold">7 letters</span>. Always include the center.
                </p>
            </div>
            <button id="spellbee-new-game" class="group flex items-center gap-2 rounded-full bg-slate-100 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-900 hover:bg-amber-400 transition-all dark:bg-slate-800 dark:text-white dark:hover:bg-amber-500">
                New Puzzle
            </button>
        </div>

        <div id="spellbee-message" class="mb-6 flex items-center justify-center min-h-[48px] rounded-2xl bg-amber-50 px-6 py-3 text-sm font-bold text-amber-800 dark:bg-amber-900/20 dark:text-amber-300 animate-pulse-subtle">
            Start by finding your first word.
        </div>

        <div class="relative mb-8 text-center">
            <input 
                id="spellbee-input" 
                type="text" 
                readonly 
                placeholder="Type or click..."
                class="w-full bg-transparent text-center text-3xl font-black uppercase tracking-[0.2em] text-slate-900 outline-none placeholder:text-slate-200 dark:text-white dark:placeholder:text-slate-800" 
            />
            <div class="mx-auto mt-2 h-1 w-24 rounded-full bg-amber-400"></div>
        </div>

        <div id="spellbee-hive" class="mx-auto mb-10 grid w-fit grid-cols-3 gap-1 p-4">
            </div>

        <div class="flex flex-wrap justify-center gap-3">
            <button id="spellbee-delete" class="flex-1 max-w-[120px] rounded-2xl border-2 border-slate-100 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-all dark:border-slate-800 dark:text-slate-400 dark:hover:border-slate-600">
                Delete
            </button>
            <button id="spellbee-shuffle" class="flex items-center justify-center w-12 h-12 rounded-full border-2 border-slate-100 text-slate-500 hover:bg-slate-50 transition-all dark:border-slate-800 dark:hover:bg-slate-800">
                <i class="fa-solid fa-shuffle text-xl"></i>
            </button>
            <button id="hint_button" type="button" class="flex items-center justify-center w-12 h-12 rounded-full border-2 border-slate-100 text-slate-500 hover:bg-amber-50 hover:text-amber-600 transition-all dark:border-slate-800 dark:hover:bg-slate-800 dark:hover:text-amber-400">
                <i class="fa-regular fa-lightbulb text-xl"></i>
            </button>
            <button id="spellbee-submit" class="flex-1 max-w-[160px] rounded-2xl bg-amber-400 py-3 text-xs font-black uppercase tracking-widest text-slate-900 shadow-lg shadow-amber-200 hover:bg-amber-500 hover:-translate-y-0.5 active:translate-y-0 transition-all dark:shadow-none">
                Enter
            </button>
        </div>
    </section>
    

    <aside class="flex flex-col gap-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-baseline justify-between mb-4">
                <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Live Stats</h2>
                <span id="spellbee-rank" class="rounded-full bg-amber-100 px-3 py-1 text-[10px] font-bold uppercase text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                    Beginner
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/50">
                    <p class="text-[10px] font-bold uppercase text-slate-400">Current Score</p>
                    <div id="spellbee-score" class="text-3xl font-black text-slate-900 dark:text-white">0</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/50">
                    <p class="text-[10px] font-bold uppercase text-slate-400">Words Found</p>
                    <div id="spellbee-words-count" class="text-3xl font-black text-slate-900 dark:text-white">0</div>
                </div>
            </div>

            <div class="mt-6">
                <div class="mb-2 flex justify-between text-[11px] font-bold uppercase text-slate-500">
                    <span>Progress</span>
                    <span id="spellbee-progress">0%</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div id="spellbee-progress-fill" class="h-full bg-amber-400 transition-all duration-500" style="width: 0%;"></div>
                </div>
            </div>

            <button id="spellbee-ranking-trigger" type="button" class="group mt-6 w-full rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-left transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-amber-500/60 dark:hover:bg-amber-900/20">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Ranking</p>
                        <p class="mt-1 text-lg font-black text-slate-900 dark:text-white">
                            <span id="spellbee-current-rank-title">Beginner</span>
                        </p>
                        <p id="spellbee-next-rank-wrapper" class="text-xs font-semibold text-slate-500 dark:text-slate-300">
                            <span class="font-bold text-amber-600 dark:text-amber-400">
                                <span id="spellbee-next-rank-left">0</span>
                            </span>
                            to
                            <span id="spellbee-next-rank-title">Novice</span>
                        </p>
                    </div>
                    <i class="fa-solid fa-chart-line text-amber-500 transition group-hover:scale-110"></i>
                </div>
                <div id="spellbee-ranking-track" class="mt-4 grid grid-cols-9 gap-1"></div>
            </button>
        </section>

        <section class="flex-1 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="mb-4 text-xs font-black uppercase tracking-widest text-slate-400">Found Words</h2>
            <ul id="spellbee-found-words" class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-2 text-sm font-medium scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-700">
                </ul>
        </section>
    </aside>

    
</main>


<div id="spellbee-ranking-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/65 p-4">
    <div class="w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-900 dark:text-white">Rankings</h3>
            <button id="spellbee-ranking-modal-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="spellbee-ranking-modal-list" class="space-y-2"></div>
    </div>
</div>

<div id="spellbee-hints-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/65 p-4">
    <div class="w-full max-w-4xl rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-900 dark:text-white">Hints</h3>
            <button id="spellbee-hints-modal-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div id="spellbee-hints-letters" class="text-center text-lg font-black uppercase tracking-[0.18em] text-slate-900 dark:text-white"></div>
            <p class="text-center text-sm font-semibold text-slate-600 dark:text-slate-300">
                <span id="spellbee-hints-word-count">0</span>
                words |
                <span id="spellbee-hints-points">0</span>
                points
            </p>

            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                <table class="min-w-full text-sm">
                    <thead id="spellbee-hints-table-head" class="bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200"></thead>
                    <tbody id="spellbee-hints-table-body" class="divide-y divide-slate-200 dark:divide-slate-700"></tbody>
                </table>
            </div>

            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-300">Two letter list</p>
                <ul id="spellbee-hints-two-letter-list" class="mt-3 grid max-h-52 grid-cols-2 gap-2 overflow-y-auto rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 sm:grid-cols-3"></ul>
            </div>
        </div>
    </div>

      
</div>


<section class="mx-auto grid max-w-6xl gap-8 px-4 py-3 sm:px-6">
 <!-- Play Other Games Section -->
          @livewire('play-more-games')
</section>


<section class="mx-auto mb-10 max-w-6xl px-4 sm:px-6">
    @include('partials.game-content-section', [
        'game' => $defaultGame,
        'fallbackTitle' => 'About SpellBee',
        'badge' => 'SpellBee Guide',
        'topSpacing' => 'mt-0',
    ])
</section>

<style>
    /* Subtle animation for the message bar */
    @keyframes pulse-subtle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    .animate-pulse-subtle {
        animation: pulse-subtle 3s ease-in-out infinite;
    }
    /* Custom Scrollbar for the word list */
    #spellbee-found-words::-webkit-scrollbar { width: 4px; }
    #spellbee-found-words::-webkit-scrollbar-track { background: transparent; }
    #spellbee-found-words::-webkit-scrollbar-thumb { border-radius: 10px; background: #e2e8f0; }
</style>

    @include('partials.game-analytics-client', ['analyticsContext' => 'spell-bee'])

    @livewire('footer')

    <script>
        (() => {
            const puzzles = [
                {
                    letters: ['B', 'E', 'I', 'R', 'S', 'U', 'V'],
                    center: 'B',
                    dictionary: [
                        'BEER', 'BEERS', 'BIBS', 'BIER', 'BIERS', 'BREE', 'BREES', 'BREVE', 'BREVES', 'BRIBE',
                        'BRIBER', 'BRIBERS', 'BRIBES', 'BRIE', 'BRIER', 'BRIERS', 'BRUISE', 'BRUISER', 'BRUISES',
                        'BURB', 'BURBS', 'BURSE', 'BUSIER', 'BUSIES', 'EBBS', 'IBIS', 'IBISES', 'REBUS', 'REBUSES',
                        'REVERB', 'RIBS', 'RUBBER', 'RUBBERS', 'RUBE', 'RUBES', 'RUBIES', 'SUBS', 'SUBURB', 'SUBURBS',
                        'VERB', 'VERBS', 'VIBE', 'VIBES', 'SUBVERSIVE', 'SUBVERSIVES'
                    ]
                },
                {
                    letters: ['D', 'A', 'F', 'I', 'L', 'O', 'S'],
                    center: 'D',
                    dictionary: [
                        'ADDS', 'ADIOS', 'AIDS', 'ALDOL', 'ALDOLS', 'DADO', 'DADOS', 'DADS', 'DAFFODIL', 'DAFFODILS',
                        'DAFFS', 'DAIS', 'DIAL', 'DIALS', 'DIFF', 'DIFFS', 'DILL', 'DILLS', 'DISS', 'DODO', 'DODOS',
                        'DOFF', 'DOFFS', 'DOLL', 'DOLLS', 'DOSS', 'FADS', 'FLOOD', 'FLOODS', 'FOLD', 'FOLDS',
                        'FOOD', 'FOODS', 'IDOL', 'IDOLS', 'LADS', 'LIDO', 'LIDOS', 'LIDS', 'LOAD', 'LOADS', 'ODDS',
                        'SAID', 'SALAD', 'SALADS', 'SLID', 'SODA', 'SODAS', 'SODS', 'SOLD', 'SOLID', 'SOLIDS', 'OFFLOAD'
                    ]
                }
            ];

            const hiveElement = document.getElementById('spellbee-hive');
            const inputElement = document.getElementById('spellbee-input');
            const messageElement = document.getElementById('spellbee-message');
            const scoreElement = document.getElementById('spellbee-score');
            const wordsCountElement = document.getElementById('spellbee-words-count');
            const rankElement = document.getElementById('spellbee-rank');
            const progressElement = document.getElementById('spellbee-progress');
            const progressFillElement = document.getElementById('spellbee-progress-fill');
            const rankingTriggerElement = document.getElementById('spellbee-ranking-trigger');
            const rankingTrackElement = document.getElementById('spellbee-ranking-track');
            const currentRankTitleElement = document.getElementById('spellbee-current-rank-title');
            const nextRankWrapperElement = document.getElementById('spellbee-next-rank-wrapper');
            const nextRankLeftElement = document.getElementById('spellbee-next-rank-left');
            const nextRankTitleElement = document.getElementById('spellbee-next-rank-title');
            const rankingModalElement = document.getElementById('spellbee-ranking-modal');
            const rankingModalCloseElement = document.getElementById('spellbee-ranking-modal-close');
            const rankingModalListElement = document.getElementById('spellbee-ranking-modal-list');

            const hintButtonElement = document.getElementById('hint_button');
            const hintsModalElement = document.getElementById('spellbee-hints-modal');
            const hintsModalCloseElement = document.getElementById('spellbee-hints-modal-close');
            const hintsLettersElement = document.getElementById('spellbee-hints-letters');
            const hintsWordCountElement = document.getElementById('spellbee-hints-word-count');
            const hintsPointsElement = document.getElementById('spellbee-hints-points');
            const hintsTableHeadElement = document.getElementById('spellbee-hints-table-head');
            const hintsTableBodyElement = document.getElementById('spellbee-hints-table-body');
            const hintsTwoLetterListElement = document.getElementById('spellbee-hints-two-letter-list');
            const foundWordsElement = document.getElementById('spellbee-found-words');

            const submitButton = document.getElementById('spellbee-submit');
            const deleteButton = document.getElementById('spellbee-delete');
            const shuffleButton = document.getElementById('spellbee-shuffle');
            const newGameButton = document.getElementById('spellbee-new-game');

            let game = null;
            let currentWord = '';
            let foundWords = new Set();
            let score = 0;
            let maxScore = 0;
            let startedAt = 0;
            let outerLetters = [];
            let rankThresholds = [];

            const rankSteps = [
                { id: 'beginner', key: 'Beginner', ratio: 0 },
                { id: 'novice', key: 'Novice', ratio: 0.03 },
                { id: 'okay', key: 'Okay', ratio: 0.07 },
                { id: 'good', key: 'Good', ratio: 0.12 },
                { id: 'solid', key: 'Solid', ratio: 0.23 },
                { id: 'nice', key: 'Nice', ratio: 0.35 },
                { id: 'great', key: 'Great', ratio: 0.56 },
                { id: 'amazing', key: 'Amazing', ratio: 0.72 },
                { id: 'genius', key: 'Genius', ratio: 1 },
            ];

            function trackAnalytics(eventType, payload = {}) {
                if (!window.WordlyAnalytics || typeof window.WordlyAnalytics.track !== 'function') {
                    return;
                }

                window.WordlyAnalytics.track({
                    game_key: 'spellbee',
                    event_type: eventType,
                    ...payload,
                });
            }

            function normalize(word) {
                return word.toUpperCase();
            }

            function scoreWord(word, isPangram) {
                let points = word.length === 4 ? 1 : word.length;

                if (isPangram) {
                    points += 7;
                }

                return points;
            }

            function computeMaxScore(dictionary, letters) {
                return dictionary.reduce((sum, word) => {
                    const unique = new Set(word.split(''));
                    const isPangram = letters.every((letter) => unique.has(letter));

                    return sum + scoreWord(word, isPangram);
                }, 0);
            }

            function buildRankThresholds() {
                let previousScore = 0;

                rankThresholds = rankSteps.map((step, index) => {
                    const stepScore = index === rankSteps.length - 1
                        ? maxScore
                        : Math.max(previousScore, Math.round(maxScore * step.ratio));

                    previousScore = stepScore;

                    return {
                        ...step,
                        score: stepScore,
                    };
                });
            }

            function getCurrentRankIndex() {
                let index = 0;

                rankThresholds.forEach((step, stepIndex) => {
                    if (score >= step.score) {
                        index = stepIndex;
                    }
                });

                return index;
            }

            function renderRankingTrack() {
                rankingTrackElement.innerHTML = '';
                const currentRankIndex = getCurrentRankIndex();

                rankThresholds.forEach((step, index) => {
                    const isActive = index <= currentRankIndex;
                    const cell = document.createElement('div');
                    const value = document.createElement('span');

                    cell.className = 'rounded-xl border px-1 py-2 text-center text-[10px] font-black transition ' +
                        (isActive
                            ? 'border-amber-400 bg-amber-200/70 text-amber-900 dark:border-amber-500 dark:bg-amber-500/30 dark:text-amber-200'
                            : 'border-slate-200 bg-white text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500');
                    value.textContent = String(step.score);
                    cell.appendChild(value);
                    rankingTrackElement.appendChild(cell);
                });
            }

            function renderRankingModal() {
                const currentRankIndex = getCurrentRankIndex();
                rankingModalListElement.innerHTML = '';

                rankThresholds.forEach((step, index) => {
                    const row = document.createElement('div');
                    const status = index < currentRankIndex
                        ? 'Achieved'
                        : (index === currentRankIndex ? 'Current' : 'Locked');
                    const statusClass = index < currentRankIndex
                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                        : (index === currentRankIndex
                            ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                            : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300');

                    row.className = 'flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/70';
                    row.innerHTML = `
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-black text-slate-700 dark:bg-slate-700 dark:text-slate-100">${index + 1}</span>
                            <div>
                                <p class="text-sm font-black text-slate-900 dark:text-white">${step.key}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-300">${step.score} points</p>
                            </div>
                        </div>
                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest ${statusClass}">${status}</span>
                    `;

                    rankingModalListElement.appendChild(row);
                });
            }

            function openModal(modalElement) {
                if (! modalElement) {
                    return;
                }

                modalElement.classList.remove('hidden');
                modalElement.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal(modalElement) {
                if (! modalElement) {
                    return;
                }

                modalElement.classList.remove('flex');
                modalElement.classList.add('hidden');

                if (
                    rankingModalElement.classList.contains('hidden') &&
                    hintsModalElement.classList.contains('hidden')
                ) {
                    document.body.classList.remove('overflow-hidden');
                }
            }

            function renderHintsModal() {
                const letters = [game.center, ...outerLetters];
                const lengthSet = new Set(game.dictionary.map((word) => word.length));
                const lengths = Array.from(lengthSet).sort((a, b) => a - b);

                const byLetterAndLength = {};
                const totalsByLength = {};
                const twoLetterCounts = {};

                letters.forEach((letter) => {
                    byLetterAndLength[letter] = {};
                    lengths.forEach((length) => {
                        byLetterAndLength[letter][length] = 0;
                    });
                });

                lengths.forEach((length) => {
                    totalsByLength[length] = 0;
                });

                game.dictionary.forEach((word) => {
                    const firstLetter = word.charAt(0);
                    const wordLength = word.length;
                    const twoLetters = word.slice(0, 2);

                    if (byLetterAndLength[firstLetter] !== undefined && byLetterAndLength[firstLetter][wordLength] !== undefined) {
                        byLetterAndLength[firstLetter][wordLength] += 1;
                    }

                    if (totalsByLength[wordLength] !== undefined) {
                        totalsByLength[wordLength] += 1;
                    }

                    if (twoLetters.length === 2) {
                        twoLetterCounts[twoLetters] = (twoLetterCounts[twoLetters] || 0) + 1;
                    }
                });

                hintsLettersElement.innerHTML = letters
                    .map((letter) => letter === game.center
                        ? `<span class="rounded-md bg-amber-300 px-2 py-1 text-slate-900 dark:bg-amber-400">${letter}</span>`
                        : `<span>${letter}</span>`)
                    .join(' ');
                hintsWordCountElement.textContent = String(game.dictionary.length);
                hintsPointsElement.textContent = String(maxScore);

                hintsTableHeadElement.innerHTML = `
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-black uppercase tracking-widest">Letter</th>
                        ${lengths.map((length) => `<th class="px-3 py-2 text-center text-xs font-black">${length}</th>`).join('')}
                        <th class="px-3 py-2 text-center text-xs font-black">∑</th>
                    </tr>
                `;

                const rows = letters.map((letter) => {
                    const rowValues = lengths.map((length) => byLetterAndLength[letter][length]);
                    const rowTotal = rowValues.reduce((sum, value) => sum + value, 0);
                    const isCenter = letter === game.center;

                    return `
                        <tr class="bg-white dark:bg-slate-900/40">
                            <th class="px-3 py-2 text-left text-xs font-black uppercase ${isCenter ? 'text-amber-700 dark:text-amber-300' : 'text-slate-600 dark:text-slate-300'}">${letter}</th>
                            ${rowValues.map((value) => `<td class="px-3 py-2 text-center text-sm text-slate-700 dark:text-slate-200">${value === 0 ? '-' : value}</td>`).join('')}
                            <td class="px-3 py-2 text-center text-sm font-black text-slate-900 dark:text-white">${rowTotal}</td>
                        </tr>
                    `;
                });

                const totalWords = Object.values(totalsByLength).reduce((sum, value) => sum + value, 0);
                rows.push(`
                    <tr class="bg-slate-100 dark:bg-slate-800">
                        <th class="px-3 py-2 text-left text-xs font-black uppercase text-slate-700 dark:text-slate-100">∑</th>
                        ${lengths.map((length) => `<td class="px-3 py-2 text-center text-sm font-black text-slate-700 dark:text-slate-100">${totalsByLength[length] === 0 ? '-' : totalsByLength[length]}</td>`).join('')}
                        <td class="px-3 py-2 text-center text-sm font-black text-slate-900 dark:text-white">${totalWords}</td>
                    </tr>
                `);

                hintsTableBodyElement.innerHTML = rows.join('');

                const pairs = Object.entries(twoLetterCounts)
                    .sort((a, b) => a[0].localeCompare(b[0]))
                    .map(([pair, count]) => `<li class="rounded-xl border border-slate-200 bg-white px-3 py-1 dark:border-slate-700 dark:bg-slate-900/70">${pair.toLowerCase()} - ${count}</li>`);

                hintsTwoLetterListElement.innerHTML = pairs.join('');
            }

            function renderHive() {
                hiveElement.innerHTML = '';
                const raisedCellIndexes = new Set([3, 5, 6, 8]);

                const slots = [
                    null,
                    outerLetters[0],
                    null,
                    outerLetters[1],
                    game.center,
                    outerLetters[2],
                    outerLetters[3],
                    outerLetters[4],
                    outerLetters[5],
                ];

                slots.forEach((letter, slotIndex) => {
                    if (!letter) {
                        const empty = document.createElement('div');
                        empty.className = 'h-20 w-20';
                        hiveElement.appendChild(empty);

                        return;
                    }

                    const button = document.createElement('button');
                    button.type = 'button';
                    const raisedClass = raisedCellIndexes.has(slotIndex) ? '-translate-y-10' : '';

                    button.className = `${raisedClass} spellbee-cell text-xl font-black transition-transform hover:scale-105 active:scale-95 ` +
                        (letter === game.center ? 'spellbee-center' : 'spellbee-outer');
                    button.textContent = letter;
                    button.addEventListener('click', () => addLetter(letter));
                    hiveElement.appendChild(button);
                });
            }

            function setMessage(text, type = 'info') {
                const classes = {
                    info: 'text-slate-600 border-slate-200 bg-slate-50 dark:text-slate-200 dark:border-slate-700 dark:bg-slate-800',
                    success: 'text-emerald-700 border-emerald-200 bg-emerald-50 dark:text-emerald-300 dark:border-emerald-800 dark:bg-emerald-900/30',
                    error: 'text-rose-700 border-rose-200 bg-rose-50 dark:text-rose-300 dark:border-rose-800 dark:bg-rose-900/30',
                    warn: 'text-amber-700 border-amber-200 bg-amber-50 dark:text-amber-300 dark:border-amber-800 dark:bg-amber-900/30',
                };

                messageElement.className = 'mb-4 min-h-10 rounded-xl border px-4 py-2 text-sm font-semibold ' + (classes[type] || classes.info);
                messageElement.textContent = text;
            }

            function updateInput() {
                inputElement.value = currentWord;
            }

            function updateStats() {
                scoreElement.textContent = String(score);
                wordsCountElement.textContent = String(foundWords.size);

                const ratio = maxScore > 0 ? score / maxScore : 0;
                const currentRankIndex = getCurrentRankIndex();
                const currentRank = rankThresholds[currentRankIndex] ?? rankThresholds[0];
                const nextRank = rankThresholds[currentRankIndex + 1] ?? null;
                const progressPercent = Math.min(100, Math.round(ratio * 100));

                rankElement.textContent = currentRank.key;
                currentRankTitleElement.textContent = currentRank.key;
                progressElement.textContent = `${progressPercent}% of max score`;
                progressFillElement.style.width = `${progressPercent}%`;

                if (nextRank) {
                    nextRankWrapperElement.classList.remove('hidden');
                    nextRankTitleElement.textContent = nextRank.key;
                    nextRankLeftElement.textContent = String(Math.max(0, nextRank.score - score));
                } else {
                    nextRankWrapperElement.classList.add('hidden');
                }

                renderRankingTrack();
                renderRankingModal();
            }

            function renderFoundWords() {
                foundWordsElement.innerHTML = '';

                if (foundWords.size === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'text-slate-500 dark:text-slate-400';
                    empty.textContent = 'No words yet.';
                    foundWordsElement.appendChild(empty);

                    return;
                }

                Array.from(foundWords)
                    .sort((a, b) => a.localeCompare(b))
                    .forEach((word) => {
                        const item = document.createElement('li');
                        item.className = 'rounded-lg border border-slate-200 px-3 py-1.5 font-semibold dark:border-slate-700';
                        item.textContent = word;
                        foundWordsElement.appendChild(item);
                    });
            }

            function addLetter(letter) {
                currentWord += letter;
                updateInput();
            }

            function deleteLetter() {
                currentWord = currentWord.slice(0, -1);
                updateInput();
            }

            function shuffleLetters() {
                for (let i = outerLetters.length - 1; i > 0; i--) {
                    const randomIndex = Math.floor(Math.random() * (i + 1));
                    [outerLetters[i], outerLetters[randomIndex]] = [outerLetters[randomIndex], outerLetters[i]];
                }

                renderHive();
                setMessage('Letters shuffled.', 'info');
            }

            function canBuildWord(candidate) {
                const allowed = new Set(game.letters);

                return candidate.split('').every((letter) => allowed.has(letter));
            }

            function submitWord() {
                const candidate = normalize(currentWord.trim());

                if (candidate.length < 4) {
                    setMessage('Words must be at least 4 letters.', 'warn');

                    return;
                }

                if (!candidate.includes(game.center)) {
                    setMessage(`Word must include the center letter "${game.center}".`, 'warn');

                    return;
                }

                if (!canBuildWord(candidate)) {
                    setMessage('Word uses letters outside the hive.', 'error');

                    return;
                }

                if (!game.dictionarySet.has(candidate)) {
                    setMessage('Word not in dictionary.', 'error');

                    return;
                }

                if (foundWords.has(candidate)) {
                    setMessage('Word already found.', 'warn');

                    return;
                }

                const unique = new Set(candidate.split(''));
                const isPangram = game.letters.every((letter) => unique.has(letter));
                const points = scoreWord(candidate, isPangram);

                foundWords.add(candidate);
                score += points;
                currentWord = '';

                updateInput();
                renderFoundWords();
                updateStats();

                setMessage(`${candidate} accepted (+${points}${isPangram ? ', pangram bonus!' : ''})`, 'success');

                trackAnalytics('word_found', {
                    score: points,
                    metadata: {
                        word: candidate,
                        pangram: isPangram,
                    },
                });

                if (foundWords.size === game.dictionary.length) {
                    setMessage('Perfect! You found all words in this puzzle.', 'success');
                    trackAnalytics('game_completed', {
                        status: 'won',
                        score,
                        duration_seconds: Math.max(0, Math.round((Date.now() - startedAt) / 1000)),
                        metadata: {
                            found_words: foundWords.size,
                            total_words: game.dictionary.length,
                        },
                    });
                }
            }

            function startGame() {
                const selectedPuzzle = puzzles[Math.floor(Math.random() * puzzles.length)];
                game = {
                    letters: selectedPuzzle.letters.map(normalize),
                    center: normalize(selectedPuzzle.center),
                    dictionary: selectedPuzzle.dictionary.map(normalize),
                };

                game.dictionarySet = new Set(game.dictionary);
                currentWord = '';
                foundWords = new Set();
                score = 0;
                maxScore = computeMaxScore(game.dictionary, game.letters);
                outerLetters = game.letters.filter((letter) => letter !== game.center);
                buildRankThresholds();
                startedAt = Date.now();

                renderHive();
                updateInput();
                renderFoundWords();
                renderHintsModal();
                updateStats();
                setMessage('New puzzle started. Find as many words as you can!', 'info');

                trackAnalytics('game_started', {
                    metadata: {
                        center_letter: game.center,
                        total_words: game.dictionary.length,
                    },
                });
            }

            submitButton.addEventListener('click', submitWord);
            deleteButton.addEventListener('click', deleteLetter);
            shuffleButton.addEventListener('click', shuffleLetters);
            newGameButton.addEventListener('click', startGame);
            rankingTriggerElement.addEventListener('click', () => openModal(rankingModalElement));
            rankingModalCloseElement.addEventListener('click', () => closeModal(rankingModalElement));
            hintButtonElement.addEventListener('click', () => openModal(hintsModalElement));
            hintsModalCloseElement.addEventListener('click', () => closeModal(hintsModalElement));

            rankingModalElement.addEventListener('click', (event) => {
                if (event.target === rankingModalElement) {
                    closeModal(rankingModalElement);
                }
            });

            hintsModalElement.addEventListener('click', (event) => {
                if (event.target === hintsModalElement) {
                    closeModal(hintsModalElement);
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal(rankingModalElement);
                    closeModal(hintsModalElement);

                    return;
                }

                const isAnyModalOpen =
                    ! rankingModalElement.classList.contains('hidden') ||
                    ! hintsModalElement.classList.contains('hidden');

                if (isAnyModalOpen) {
                    return;
                }

                const key = event.key.toUpperCase();

                if (event.key === 'Enter') {
                    event.preventDefault();
                    submitWord();

                    return;
                }

                if (event.key === 'Backspace') {
                    event.preventDefault();
                    deleteLetter();

                    return;
                }

                if (/^[A-Z]$/.test(key) && game.letters.includes(key)) {
                    event.preventDefault();
                    addLetter(key);
                }
            });

            startGame();
        })();
    </script>
</body>

</html>
