<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --font-ui: 'Inter', sans-serif;
            --font-heading: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-ui);
            -webkit-tap-highlight-color: transparent;
        }

        h1,
        h2,
        h3 {
            font-family: var(--font-heading);
        }

        /* Smooth theme transitions */
        html {
            transition: background-color 0.3s ease;
        }

        body,
        nav,
        .tile,
        button {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Responsive Tile Sizing */
        .tile {
            width: clamp(50px, 15vw, 64px);
            height: clamp(50px, 15vw, 64px);
            font-size: clamp(1.5rem, 5vw, 2rem);
        }

        /* Smooth scroll for mobile sections */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Enhanced tile states for better visibility */
        .tile-empty {
            background: white;
            border-color: rgb(226 232 240);
        }

        .dark .tile-empty {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .tile-filled {
            border-color: rgb(148 163 184);
        }

        .dark .tile-filled {
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Better contrast for game tiles */
        .tile-correct {
            background: #10b981 !important;
            border-color: #10b981 !important;
        }

        .tile-present {
            background: #f59e0b !important;
            border-color: #f59e0b !important;
        }

        .tile-absent {
            background: #64748b !important;
            border-color: #64748b !important;
        }

        .dark .tile-absent {
            background: #475569 !important;
            border-color: #475569 !important;
        }
    </style>
    
    <script>
        // Theme initialization - Load saved preference or use system preference
        (function() {
            const savedTheme = localStorage.getItem('wordly-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('wordly-theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        })();
    </script>
</head>

<body class="bg-white text-slate-900 dark:bg-[#08090a] dark:text-slate-100 transition-colors duration-300">
  @php
      $wordLengthOptions = [4, 5, 6, 7, 8, 9, 10, 11];
      $homeI18n = [
          'wordLengthSelected' => __('home.word_length_selected', ['count' => ':count']),
          'notInWordList' => __('home.not_in_word_list'),
          'excellent' => __('home.excellent'),
          'gameOverWord' => __('home.game_over_word', ['word' => ':word']),
      ];
  @endphp

  @livewire('nav-bar')

    <main class="max-w-7xl mx-auto px-3 py-3 md:py-8">
        <div class="grid grid-cols-1">
             {{-- @livewire('ads-left-side') --}}
        
            <section class="lg:col-span-6 flex flex-col items-center">
                <div class="w-full md:max-w-lg mb-3">
                    <div class="mx-auto w-full max-w-xs">
                        <label for="word-length-select" class="hidden md:block text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">{{ __('home.word_length') }}</label>
                        <select
                            id="word-length-select"
                            onchange="changeWordLength(this.value)"
                            class="w-full rounded-xl border border-slate-200 bg-white px-2 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/30 dark:border-white/10 dark:bg-white/5 dark:text-slate-200"
                        >
                            @foreach ($wordLengthOptions as $wordLengthOption)
                                <option value="{{ $wordLengthOption }}" @selected($wordLengthOption === 5)>
                                    {{ __('home.letter_words', ['count' => $wordLengthOption]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="game-board" class="grid grid-rows-5 gap-2 mb-3">
                </div>
        
                <div class="w-full md:max-w-lg space-y-2 select-none mb-12">
                    <div class="flex justify-center gap-1 md:gap-2">
                        <script> ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'].forEach(l => document.write(`<button onclick="handleInput('${l}')" id="key-${l}" class="flex-1 h-12 md:h-16 bg-slate-100 dark:bg-white/10 rounded font-semibold text-base sm:text-lg hover:bg-slate-200 dark:hover:bg-white/20 active:scale-90 transition-all shadow-sm">${l}</button>`)); </script>
                    </div>
                    <div class="flex justify-center gap-1 md:gap-2 px-4 sm:px-6">
                        <script> ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'].forEach(l => document.write(`<button onclick="handleInput('${l}')" id="key-${l}" class="flex-1 h-12 md:h-16 bg-slate-100 dark:bg-white/10 rounded font-semibold text-base sm:text-lg hover:bg-slate-200 dark:hover:bg-white/20 active:scale-90 transition-all shadow-sm">${l}</button>`)); </script>
                    </div>
                    <div class="flex justify-center gap-1 md:gap-2">
                        <button onclick="handleInput('BACKSPACE')" class="px-5 h-12 md:h-16 bg-slate-300 dark:bg-white/20 rounded text-lg"><i class="fa-solid fa-backspace"></i></button>
                        <script> ['Z', 'X', 'C', 'V', 'B', 'N', 'M'].forEach(l => document.write(`<button onclick="handleInput('${l}')" id="key-${l}" class="flex-1 h-12 md:h-16 bg-slate-100 dark:bg-white/10 rounded font-semibold text-base sm:text-lg hover:bg-slate-200 dark:hover:bg-white/20 active:scale-90 transition-all shadow-sm">${l}</button>`)); </script>
                        <button onclick="handleInput('ENTER')" class="px-4 h-12 md:h-16 bg-slate-300 dark:bg-white/20 rounded font-bold text-xs sm:text-sm uppercase tracking-tighter">{{ __('home.enter') }}</button>
                    </div>
                </div>
        
                <div aria-labelledby="games-heading">
                    </div>
            </section>
        
            {{-- @livewire('ads-right-side') --}}
        </div>
        
        @include('partials.game-analytics-client', ['analyticsContext' => 'home'])

        <script>
        /** GAME LOGIC **/
        const MAX_ATTEMPTS = 5;
        const DEFAULT_WORD_LENGTH = 5;
        const REVEAL_DELAY = 220;
        const POST_REVEAL_DELAY = 450;
        const I18N = @json($homeI18n);

        const FIVE_LETTER_WORDS = ["APPLE", "BEACH", "BRAIN", "BREAD", "CLOUD", "CRANE", "DREAM", "FLAME", "GRAPE", "HEART", "LIGHT", "MUSIC", "OCEAN", "PIANO", "PLANT", "POWER", "SMILE", "SNAKE", "STONE", "TIGER", "TRAIN", "WATER", "WHALE", "WORLD"];

        // Valid words dictionary (expanded for better validation)
        const VALID_WORDS = new Set([
            ...FIVE_LETTER_WORDS,
            "ABOUT", "ABOVE", "ACTOR", "ADULT", "AFTER", "AGAIN", "ANGEL", "ANGER", "ANGLE", "ANGRY",
            "APART", "ARENA", "ARGUE", "ARISE", "ARRAY", "ARROW", "ASIDE", "ASSET", "AUDIO", "AVOID",
            "AWAKE", "AWARD", "AWARE", "BADGE", "BAKER", "BASES", "BASIC", "BASIN", "BASIS", "BATCH",
            "BEACH", "BEAST", "BEGAN", "BEGIN", "BEING", "BENCH", "BILLY", "BIRTH", "BLACK", "BLADE",
            "BLAME", "BLANK", "BLAST", "BLEED", "BLESS", "BLIND", "BLOCK", "BLOOD", "BLOOM", "BLUES",
            "BOARD", "BOOST", "BOOTH", "BOUND", "BRAIN", "BRAKE", "BRAND", "BRASS", "BRAVE", "BREAD",
            "BREAK", "BREED", "BRICK", "BRIDE", "BRIEF", "BRING", "BROAD", "BROKE", "BROWN", "BUILD",
            "BUILT", "BUYER", "CABLE", "CALIF", "CARRY", "CATCH", "CAUSE", "CHAIN", "CHAIR", "CHAOS",
            "CHARM", "CHART", "CHASE", "CHEAP", "CHECK", "CHEST", "CHIEF", "CHILD", "CHINA", "CHOSE",
            "CIVIL", "CLAIM", "CLASS", "CLEAN", "CLEAR", "CLICK", "CLIFF", "CLIMB", "CLOCK", "CLOSE",
            "COACH", "COAST", "COUNT", "COURT", "COVER", "CRACK", "CRAFT", "CRASH", "CRAZY", "CREAM",
            "CRIME", "CROSS", "CROWD", "CROWN", "CRUDE", "CURVE", "CYCLE", "DAILY", "DANCE", "DATED",
            "DEALT", "DEATH", "DEBUT", "DELAY", "DELTA", "DENSE", "DEPTH", "DOING", "DOUBT", "DOZEN",
            "DRAFT", "DRAMA", "DRANK", "DRAWN", "DREAM", "DRESS", "DRIED", "DRILL", "DRINK", "DRIVE",
            "DROVE", "DYING", "EAGER", "EARLY", "EARTH", "EIGHT", "ELITE", "EMPTY", "ENEMY", "ENJOY",
            "ENTER", "ENTRY", "EQUAL", "ERROR", "EVENT", "EVERY", "EXACT", "EXIST", "EXTRA", "FAITH",
            "FALSE", "FAULT", "FIBER", "FIELD", "FIFTH", "FIFTY", "FIGHT", "FINAL", "FIRST", "FIXED",
            "FLASH", "FLEET", "FLESH", "FLIGHT", "FLOOR", "FLUID", "FOCUS", "FORCE", "FORTH", "FORTY",
            "FORUM", "FOUND", "FRAME", "FRANK", "FRAUD", "FRESH", "FRONT", "FRUIT", "FULLY", "FUNNY",
            "GIANT", "GIVEN", "GLASS", "GLOBE", "GOING", "GRACE", "GRADE", "GRAND", "GRANT", "GRASS",
            "GRAVE", "GREAT", "GREEN", "GROSS", "GROUP", "GROWN", "GUARD", "GUESS", "GUEST", "GUIDE",
            "HAPPY", "HARSH", "HEART", "HEAVY", "HORSE", "HOTEL", "HOUSE", "HUMAN", "IDEAL", "IMAGE",
            "INDEX", "INNER", "INPUT", "ISSUE", "JAPAN", "JONES", "JUDGE", "KNOWN", "LABEL", "LARGE",
            "LASER", "LATER", "LAUGH", "LAYER", "LEARN", "LEASE", "LEAST", "LEAVE", "LEGAL", "LEMON",
            "LEVEL", "LEWIS", "LIGHT", "LIMIT", "LINKS", "LIVES", "LOCAL", "LOGIC", "LOOSE", "LOWER",
            "LUCKY", "LUNCH", "LYING", "MAGIC", "MAJOR", "MAKER", "MARCH", "MARIA", "MATCH", "MAYBE",
            "MAYOR", "MEANT", "MEDIA", "METAL", "MIGHT", "MINOR", "MINUS", "MIXED", "MODEL", "MONEY",
            "MONTH", "MORAL", "MOTOR", "MOUNT", "MOUSE", "MOUTH", "MOVED", "MOVIE", "MUSIC", "NEEDS",
            "NEVER", "NEWLY", "NIGHT", "NOISE", "NORTH", "NOTED", "NOVEL", "NURSE", "OCCUR", "OCEAN",
            "OFFER", "OFTEN", "ORDER", "OTHER", "OUGHT", "PAINT", "PANEL", "PAPER", "PARTY", "PEACE",
            "PETER", "PHASE", "PHONE", "PHOTO", "PIANO", "PIECE", "PILOT", "PITCH", "PLACE", "PLAIN",
            "PLANE", "PLANT", "PLATE", "POINT", "POUND", "POWER", "PRESS", "PRICE", "PRIDE", "PRIME",
            "PRINT", "PRIOR", "PRIZE", "PROOF", "PROUD", "PROVE", "QUEEN", "QUICK", "QUIET", "QUITE",
            "RADIO", "RAISE", "RANGE", "RAPID", "RATIO", "REACH", "READY", "REFER", "RIGHT", "RIVAL",
            "RIVER", "ROBIN", "ROGER", "ROMAN", "ROUGH", "ROUND", "ROUTE", "ROYAL", "RURAL", "SCALE",
            "SCENE", "SCOPE", "SCORE", "SENSE", "SERVE", "SEVEN", "SHALL", "SHAPE", "SHARE", "SHARP",
            "SHEET", "SHELF", "SHELL", "SHIFT", "SHIRT", "SHOCK", "SHOOT", "SHORT", "SHOWN", "SIGHT",
            "SINCE", "SIXTH", "SIZED", "SKILL", "SLEEP", "SLIDE", "SMALL", "SMART", "SMILE", "SMITH",
            "SMOKE", "SOLID", "SOLVE", "SORRY", "SOUND", "SOUTH", "SPACE", "SPARE", "SPEAK", "SPEED",
            "SPEND", "SPENT", "SPLIT", "SPOKE", "SPORT", "STAFF", "STAGE", "STAKE", "STAND", "START",
            "STATE", "STEAM", "STEEL", "STICK", "STILL", "STOCK", "STONE", "STOOD", "STORE", "STORM",
            "STORY", "STRIP", "STUCK", "STUDY", "STUFF", "STYLE", "SUGAR", "SUITE", "SUPER", "SWEET",
            "TABLE", "TAKEN", "TASTE", "TAXES", "TEACH", "TEETH", "TERRY", "TEXAS", "THANK", "THEFT",
            "THEIR", "THEME", "THERE", "THESE", "THICK", "THING", "THINK", "THIRD", "THOSE", "THREE",
            "THREW", "THROW", "TIGHT", "TIMES", "TITLE", "TODAY", "TOPIC", "TOTAL", "TOUCH", "TOUGH",
            "TOWER", "TRACK", "TRADE", "TRAIL", "TRAIN", "TRAIT", "TREAT", "TREND", "TRIAL", "TRIBE",
            "TRICK", "TRIED", "TRIES", "TROOP", "TRUCK", "TRULY", "TRUMP", "TRUST", "TRUTH", "TWICE",
            "UNDER", "UNDUE", "UNION", "UNITY", "UNTIL", "UPPER", "URBAN", "USAGE", "USUAL", "VALID",
            "VALUE", "VIDEO", "VIRUS", "VISIT", "VITAL", "VOCAL", "VOICE", "WASTE", "WATCH", "WATER",
            "WHEEL", "WHERE", "WHICH", "WHILE", "WHITE", "WHOLE", "WHOSE", "WOMAN", "WOMEN", "WORLD",
            "WORRY", "WORSE", "WORST", "WORTH", "WOULD", "WOUND", "WRITE", "WRONG", "WROTE", "YIELD",
            "YOUNG", "YOUTH"
        ]);

        const WORD_BANK = {
            4: [
                "ABLE", "ACID", "AGED", "AREA", "ARMY", "AWAY", "BABY", "BACK", "BALL", "BAND",
                "BANK", "BASE", "BELL", "BIRD", "BLUE", "BOAT", "BODY", "BOOK", "BORN", "BOWL",
                "CAMP", "CARD", "CARE", "CASE", "CASH", "CITY", "CLUB", "COAL", "COAT", "CODE",
                "COLD", "COME", "COOK", "COOL", "COST", "DARK", "DATA", "DATE", "DAWN", "DEAL",
                "DEAR", "DEEP", "DOOR", "DOWN", "DRAW", "DROP", "EACH", "EARN", "EAST", "EDGE",
                "EVEN", "FACE", "FACT", "FAIL", "FAIR", "FALL", "FARM", "FAST", "FEAR", "FEEL",
                "FIRE", "FISH", "FIVE", "FLOW", "FOOD", "FORM", "FOUR", "FREE", "FROM", "FULL",
                "FUND", "GAME", "GATE", "GIFT", "GIVE", "GOAL", "GOLD", "GOOD", "GROW", "HAIR",
                "HALF", "HAND", "HARD", "HAVE", "HEAD", "HEAR", "HEAT", "HELP", "HERE", "HIGH",
                "HOLD", "HOME", "HOPE", "HOUR", "IDEA", "JOIN", "JUMP", "JUST", "KEEP", "KIND",
                "KING", "KNOW", "LAND", "LAST", "LATE", "LEAD", "LEFT", "LIFE", "LIKE", "LINE",
                "LIST", "LIVE", "LOCK", "LONG", "LOOK", "LORD", "LOVE", "MADE", "MAIN", "MAKE",
                "MARK", "MEAL", "MEAN", "MEET", "MIND", "MISS", "MOON", "MORE", "MOST", "MOVE",
                "NAME", "NEAR", "NEED", "NEWS", "NEXT", "NICE", "NOTE", "OPEN", "OVER", "PAGE",
                "PAIN", "PAIR", "PARK", "PART", "PASS", "PATH", "PEAK", "PICK", "PLAN", "PLAY",
                "PLUS", "PULL", "PUSH", "RACE", "RAIN", "RANK", "RATE", "READ", "REAL", "REST",
                "RICH", "RIDE", "RING", "RISE", "RISK", "ROAD", "ROCK", "ROLE", "ROOM", "RULE",
                "SAFE", "SALE", "SAME", "SAND", "SAVE", "SEAT", "SEED", "SEEK", "SEEN", "SELL",
                "SEND", "SHIP", "SHOP", "SHOW", "SIDE", "SIGN", "SITE", "SIZE", "SKIN", "SLOW",
                "SNOW", "SOFT", "SOIL", "SOLD", "SOME", "SONG", "SOON", "SORT", "STAR", "STAY",
                "STEP", "STOP", "SUIT", "SURE", "TAKE", "TALE", "TALK", "TALL", "TEAM", "TELL",
                "TERM", "TEST", "TEXT", "THAN", "THAT", "THEM", "THEN", "THEY", "THIS", "TIME",
                "TOLD", "TOOK", "TOOL", "TOUR", "TOWN", "TREE", "TRIP", "TRUE", "TURN", "TYPE",
                "UNIT", "UPON", "USED", "USER", "VARY", "VERY", "VIEW", "VOTE", "WAIT", "WAKE",
                "WALK", "WALL", "WANT", "WARM", "WASH", "WAVE", "WAYS", "WEAR", "WEEK", "WELL",
                "WERE", "WEST", "WHAT", "WHEN", "WHOM", "WIDE", "WIFE", "WILD", "WILL", "WIND",
                "WINE", "WING", "WIRE", "WISE", "WISH", "WITH", "WOOD", "WORD", "WORK", "YARD",
                "YEAR", "YOUR", "ZERO", "ZONE"
            ],
            5: FIVE_LETTER_WORDS,
            6: [
                "ANCHOR", "BANANA", "BOTTLE", "BRANCH", "BRIDGE", "BUTTON", "CANDLE", "CASTLE", "CIRCLE", "COFFEE",
                "DESERT", "DRAGON", "ENGINE", "FLOWER", "FOREST", "FUTURE", "GARDEN", "GUITAR", "HARBOR", "ISLAND",
                "JACKET", "JUNGLE", "LAPTOP", "MARKET", "MELODY", "MIRROR", "MOTION", "NATION", "ORANGE", "POCKET",
                "QUARTZ", "RABBIT", "SAFETY", "SILVER", "SPIRIT", "STREAM", "SUMMER", "THRIVE", "TRAVEL", "WINTER"
            ],
            7: [
                "ABILITY", "BALANCE", "CAPTAIN", "CRYSTAL", "EMERALD", "FANTASY", "GLIMMER", "HARMONY", "JOURNEY", "KINGDOM",
                "LIBRARY", "MYSTERY", "NATURAL", "ORCHARD", "PATTERN", "QUANTUM", "RAINBOW", "SILENCE", "THUNDER", "VICTORY",
                "WARRIOR", "ZEALOUS"
            ],
            8: [
                "AIRPLANE", "BASEBALL", "BUILDING", "CHAMPION", "DISCOVER", "ELEPHANT", "FESTIVAL", "GRAPHICS", "HOSPITAL", "KEYBOARD",
                "LANGUAGE", "MOUNTAIN", "NOTEBOOK", "PAINTING", "QUESTION", "RAILROAD", "SUNSHINE", "TREASURE", "UMBRELLA", "VACATION",
                "WILDLIFE", "YEARBOOK"
            ],
            9: [
                "AFTERNOON", "BLUEPRINT", "CHOCOLATE", "DREAMLAND", "EDUCATION", "FIREPLACE", "HANDSHAKE", "IMPORTANT", "JELLYFISH", "KNOWLEDGE",
                "LANDSCAPE", "MAGNETISM", "NIGHTFALL", "OVERSIGHT", "PINEAPPLE", "QUICKSAND", "RAINSTORM", "STARLIGHT", "TREASURES", "VIEWPOINT",
                "WATERFALL", "YOUNGSTER"
            ],
            10: [
                "BASKETBALL", "BLACKBOARD", "BODYGUARDS", "BOOKMARKED", "CROSSWORDS", "EVERYTHING", "FRAMEWORKS", "HEADPHONES", "HIGHLIGHTS", "JELLYBEANS",
                "LIFEGUARDS", "NEWSPAPERS", "NORTHBOUND", "PLAYGROUND", "RIVERBANKS", "SNOWFLAKES", "SPACECRAFT", "SUNFLOWERS", "WATERPROOF", "CHEESECAKE"
            ],
            11: [
                "BACKGROUNDS", "BUTTERFLIES", "CELEBRATION", "COLLECTIONS", "DIRECTIONAL", "FOUNDATIONS", "HOUSEHOLDER", "MASTERPIECE", "QUESTIONING", "RAINFORESTS",
                "SCREENSHOTS", "THUNDERBOLT", "POWERHOUSES", "WATERFRONTS"
            ],
        };

        let currentWordLength = DEFAULT_WORD_LENGTH;
        let targetWord = "";
        let currentRow = 0;
        let currentCol = 0;
        let currentGuess = "";
        let isGameOver = false;
        let gameStartedAt = Date.now();
        let gameStats = loadStats();

        function trackGameEvent(gameKey, eventType, payload = {}) {
            if (! window.WordlyAnalytics || typeof window.WordlyAnalytics.track !== 'function') {
                return;
            }

            window.WordlyAnalytics.track({
                game_key: gameKey,
                event_type: eventType,
                ...payload,
            });
        }

        // Load statistics from localStorage
        function loadStats() {
            const saved = localStorage.getItem('wordlyStats');
            if (saved) {
                return JSON.parse(saved);
            }
            return { played: 0, won: 0, currentStreak: 0, maxStreak: 0 };
        }

        // Save statistics to localStorage
        function saveStats() {
            localStorage.setItem('wordlyStats', JSON.stringify(gameStats));
            updateStatsDisplay();
        }

        // Update stats display
        function updateStatsDisplay() {
            const winRate = gameStats.played > 0 ? Math.round((gameStats.won / gameStats.played) * 100) : 0;
            const winRateEl = document.getElementById('win-rate');
            const streakEl = document.getElementById('streak-count');
            if (winRateEl) winRateEl.textContent = winRate + '%';
            if (streakEl) streakEl.textContent = String(gameStats.currentStreak).padStart(2, '0');
        }

        // Toggle theme with localStorage persistence
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('wordly-theme', isDark ? 'dark' : 'light');
            
            // Optional: Add a brief visual feedback
            const themeBtn = typeof event !== 'undefined' ? event.currentTarget : null;

            if (themeBtn) {
                themeBtn.style.transform = 'scale(0.9) rotate(180deg)';
                setTimeout(() => {
                    themeBtn.style.transform = '';
                }, 300);
            }
        }

        function getTileMetrics(wordLength) {
            if (wordLength <= 5) {
                return { size: 'clamp(50px, 15vw, 64px)', fontSize: 'clamp(1.5rem, 5vw, 2rem)', rowGap: '0.5rem' };
            }

            if (wordLength <= 7) {
                return { size: 'clamp(44px, 11vw, 56px)', fontSize: 'clamp(1.2rem, 4.2vw, 1.6rem)', rowGap: '0.5rem' };
            }

            if (wordLength <= 9) {
                return { size: 'clamp(34px, 8.6vw, 46px)', fontSize: 'clamp(1rem, 3.6vw, 1.35rem)', rowGap: '0.35rem' };
            }

            return { size: 'clamp(26px, 6.8vw, 34px)', fontSize: 'clamp(0.8rem, 3vw, 1.1rem)', rowGap: '0.25rem' };
        }

        function randomWordForLength(wordLength) {
            const dictionary = WORD_BANK[wordLength] ?? WORD_BANK[DEFAULT_WORD_LENGTH];

            return dictionary[Math.floor(Math.random() * dictionary.length)];
        }

        function renderBoard() {
            const board = document.getElementById('game-board');

            if (!board) {
                return;
            }

            const tileMetrics = getTileMetrics(currentWordLength);
            board.innerHTML = '';

            for (let i = 0; i < MAX_ATTEMPTS; i++) {
                const row = document.createElement('div');
                row.id = `row-${i}`;
                row.className = 'flex justify-center';
                row.style.gap = tileMetrics.rowGap;

                for (let j = 0; j < currentWordLength; j++) {
                    const tile = document.createElement('div');
                    tile.id = `tile-${i}-${j}`;
                    tile.className = 'tile border-2 border-slate-200 dark:border-white/10 flex items-center justify-center font-bold rounded-lg bg-white dark:bg-white/5 transition-all';
                    tile.style.width = tileMetrics.size;
                    tile.style.height = tileMetrics.size;
                    tile.style.fontSize = tileMetrics.fontSize;
                    row.appendChild(tile);
                }

                board.appendChild(row);
            }
        }

        function resetKeyboardState() {
            document.querySelectorAll('[id^="key-"]').forEach((key) => {
                key.classList.remove('bg-green-500', 'bg-amber-500', 'text-white', 'opacity-50', 'bg-slate-400', 'dark:bg-slate-600');
            });
        }

        function initializeGame(wordLength = DEFAULT_WORD_LENGTH) {
            currentWordLength = wordLength;
            targetWord = randomWordForLength(wordLength);
            currentRow = 0;
            currentCol = 0;
            currentGuess = '';
            isGameOver = false;
            gameStartedAt = Date.now();

            renderBoard();
            resetKeyboardState();

            const selector = document.getElementById('word-length-select');

            if (selector) {
                selector.value = String(wordLength);
            }

            trackGameEvent('wordle', 'game_started', {
                word_length: wordLength,
                metadata: {
                    mode: 'classic',
                },
            });
        }

        function interpolate(template, replacements = {}) {
            return Object.entries(replacements).reduce(
                (translatedText, [key, value]) => translatedText.replaceAll(`:${key}`, String(value)),
                template,
            );
        }

        function changeWordLength(wordLength) {
            const parsedLength = Number.parseInt(wordLength, 10);

            if (! Number.isInteger(parsedLength) || ! Object.prototype.hasOwnProperty.call(WORD_BANK, parsedLength)) {
                return;
            }

            initializeGame(parsedLength);
            showMessage(interpolate(I18N.wordLengthSelected, { count: parsedLength }));
        }

        function isGuessValid(guess) {
            if (currentWordLength === DEFAULT_WORD_LENGTH) {
                return VALID_WORDS.has(guess);
            }

            return /^[A-Z]+$/.test(guess) && guess.length === currentWordLength;
        }

        function handleInput(key) {
            if (isGameOver) {
                return;
            }

            if (key === 'ENTER') {
                submitGuess();

                return;
            }

            if (key === 'BACKSPACE') {
                deleteLetter();

                return;
            }

            if (currentGuess.length < currentWordLength && key.length === 1 && /^[A-Z]$/.test(key)) {
                addLetter(key);
            }
        }

        function addLetter(letter) {
            if (currentCol >= currentWordLength) {
                return;
            }

            const tile = document.getElementById(`tile-${currentRow}-${currentCol}`);

            if (! tile) {
                return;
            }

            tile.textContent = letter;
            tile.classList.add('border-slate-400', 'dark:border-white/40');
            tile.style.transform = 'scale(1.1)';
            setTimeout(() => {
                tile.style.transform = 'scale(1)';
            }, 100);

            currentGuess += letter;
            currentCol++;
        }

        function deleteLetter() {
            if (currentCol <= 0) {
                return;
            }

            currentCol--;
            currentGuess = currentGuess.slice(0, -1);
            const tile = document.getElementById(`tile-${currentRow}-${currentCol}`);

            if (! tile) {
                return;
            }

            tile.textContent = '';
            tile.classList.remove('border-slate-400', 'dark:border-white/40');
        }

        function submitGuess() {
            if (currentGuess.length !== currentWordLength) {
                shakeTiles();

                return;
            }

            if (! isGuessValid(currentGuess)) {
                shakeTiles();
                showMessage(I18N.notInWordList);

                return;
            }

            evaluateGuess();
        }

        function shakeTiles() {
            const row = document.getElementById(`row-${currentRow}`);

            if (! row) {
                return;
            }

            row.style.animation = 'shake 0.5s';
            setTimeout(() => {
                row.style.animation = '';
            }, 500);
        }

        function showMessage(text) {
            const msg = document.createElement('div');
            msg.textContent = text;
            msg.className = 'fixed top-20 left-1/2 -translate-x-1/2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3 rounded-xl font-bold text-sm shadow-lg z-50';
            document.body.appendChild(msg);
            setTimeout(() => msg.remove(), 2000);
        }

        function evaluateGuess() {
            const guessArr = currentGuess.split('');
            const targetArr = targetWord.split('');
            const result = Array(currentWordLength).fill('absent');
            const targetLetterCount = {};

            targetArr.forEach((letter) => {
                targetLetterCount[letter] = (targetLetterCount[letter] || 0) + 1;
            });

            guessArr.forEach((letter, i) => {
                if (letter === targetArr[i]) {
                    result[i] = 'correct';
                    targetLetterCount[letter]--;
                }
            });

            guessArr.forEach((letter, i) => {
                if (result[i] === 'absent' && targetLetterCount[letter] > 0) {
                    result[i] = 'present';
                    targetLetterCount[letter]--;
                }
            });

            result.forEach((status, i) => {
                const tile = document.getElementById(`tile-${currentRow}-${i}`);
                const key = document.getElementById(`key-${guessArr[i]}`);

                if (! tile) {
                    return;
                }

                setTimeout(() => {
                    tile.style.transform = 'rotateX(90deg)';

                    setTimeout(() => {
                        tile.classList.remove('bg-white', 'dark:bg-white/5', 'border-slate-200', 'dark:border-white/10');

                        if (status === 'correct') {
                            tile.classList.add('tile-correct', 'text-white');
                            tile.style.backgroundColor = '#10b981';
                            tile.style.borderColor = '#10b981';

                            if (key && ! key.classList.contains('bg-green-500')) {
                                key.classList.remove('bg-amber-500');
                                key.classList.add('bg-green-500', 'text-white');
                            }
                        } else if (status === 'present') {
                            tile.classList.add('tile-present', 'text-white');
                            tile.style.backgroundColor = '#f59e0b';
                            tile.style.borderColor = '#f59e0b';

                            if (key && ! key.classList.contains('bg-green-500')) {
                                key.classList.add('bg-amber-500', 'text-white');
                            }
                        } else {
                            tile.classList.add('tile-absent', 'text-white');
                            const isDark = document.documentElement.classList.contains('dark');
                            tile.style.backgroundColor = isDark ? '#475569' : '#64748b';
                            tile.style.borderColor = isDark ? '#475569' : '#64748b';

                            if (key && ! key.classList.contains('bg-green-500') && ! key.classList.contains('bg-amber-500')) {
                                key.classList.add('opacity-50', 'bg-slate-400', 'dark:bg-slate-600');
                            }
                        }

                        tile.style.transform = 'rotateX(0deg)';
                    }, 250);
                }, i * REVEAL_DELAY);
            });

            const resolutionDelay = currentWordLength * REVEAL_DELAY + POST_REVEAL_DELAY;

            setTimeout(() => {
                if (currentGuess === targetWord) {
                    isGameOver = true;
                    gameStats.played++;
                    gameStats.won++;
                    gameStats.currentStreak++;
                    gameStats.maxStreak = Math.max(gameStats.maxStreak, gameStats.currentStreak);
                    saveStats();
                    bounceTiles();
                    trackGameEvent('wordle', 'game_completed', {
                        status: 'won',
                        attempts: currentRow + 1,
                        word_length: currentWordLength,
                        duration_seconds: Math.max(0, Math.round((Date.now() - gameStartedAt) / 1000)),
                    });
                    setTimeout(() => showMessage(I18N.excellent), 500);
                } else if (currentRow === MAX_ATTEMPTS - 1) {
                    isGameOver = true;
                    gameStats.played++;
                    gameStats.currentStreak = 0;
                    saveStats();
                    trackGameEvent('wordle', 'game_completed', {
                        status: 'lost',
                        attempts: MAX_ATTEMPTS,
                        word_length: currentWordLength,
                        duration_seconds: Math.max(0, Math.round((Date.now() - gameStartedAt) / 1000)),
                    });
                    setTimeout(() => showMessage(interpolate(I18N.gameOverWord, { word: targetWord })), 500);
                } else {
                    currentRow++;
                    currentCol = 0;
                    currentGuess = '';
                }
            }, resolutionDelay);
        }

        function bounceTiles() {
            for (let i = 0; i < currentWordLength; i++) {
                const tile = document.getElementById(`tile-${currentRow}-${i}`);

                if (! tile) {
                    continue;
                }

                setTimeout(() => {
                    tile.style.animation = 'bounce 0.5s';
                    setTimeout(() => {
                        tile.style.animation = '';
                    }, 500);
                }, i * 80);
            }
        }

        window.changeWordLength = changeWordLength;
        window.handleInput = handleInput;

        // Allow physical keyboard support
        window.addEventListener('keydown', (e) => {
            const activeTag = document.activeElement?.tagName;

            if (activeTag === 'INPUT' || activeTag === 'TEXTAREA' || activeTag === 'SELECT') {
                return;
            }

            const key = e.key.toUpperCase();

            if (key === 'ENTER' || key === 'BACKSPACE' || /^[A-Z]$/.test(key)) {
                e.preventDefault();
                handleInput(key);
            }
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }
            .tile {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);

        // Update absent tile colors when theme changes
        function updateAbsentTileColors() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.tile-absent').forEach(tile => {
                tile.style.backgroundColor = isDark ? '#475569' : '#64748b';
                tile.style.borderColor = isDark ? '#475569' : '#64748b';
            });
        }

        // Watch for class changes on html element (theme toggle)
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateAbsentTileColors();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Initialize stats display and a 5-letter game by default
        updateStatsDisplay();
        initializeGame(DEFAULT_WORD_LENGTH);

        document.querySelectorAll('[data-game-track]').forEach((element) => {
            element.addEventListener('click', () => {
                const gameKey = element.getAttribute('data-game-track');
                const eventType = element.getAttribute('data-game-track-event') || 'open_game';

                if (! gameKey) {
                    return;
                }

                trackGameEvent(gameKey, eventType, {
                    metadata: {
                        source: 'home-more-games',
                    },
                });
            });
        });
        </script>

        <div class="max-w-7xl mx-auto px-1.5 md:px-6 py-10">
{{-- 
            <article
                class="relative overflow-hidden rounded-[1rem] bg-white dark:bg-[#111318] border border-slate-200/60 dark:border-white/5 p-6 md:p-16 shadow-2xl shadow-slate-200/50 dark:shadow-none mb-16">
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-green-500/10 dark:bg-green-500/5 blur-[100px] rounded-full">
                </div>
                <div
                    class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 dark:bg-blue-500/5 blur-[100px] rounded-full">
                </div>

                <div class="relative z-10 max-w-3xl">
                    <header class="mb-8">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/10 text-green-600 dark:text-green-400 text-[11px] font-bold uppercase tracking-wider mb-4">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            The Original Wordly
                        </div>
                        <h2
                            class="text-3xl md:text-5xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                            Wordly Game: <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-emerald-400">Guess
                                the Hidden Word</span>
                        </h2>
                    </header>

                    <div class="space-y-6 text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                        <p>
                            The rules are elegantly simple: your mission is to uncover a hidden word, ranging from
                            <strong class="text-slate-900 dark:text-white font-semibold">4 to 11 letters</strong>,
                            within just six attempts.
                        </p>
                        <p>
                            Each guess provides instant, color-coded feedback. A <span
                                class="text-green-600 dark:text-green-400 font-bold">Green</span> highlight confirms a
                            perfect match in the right spot, <span class="text-yellow-500 font-bold">Yellow</span>
                            indicates the letter exists but is currently misplaced, and <span
                                class="text-slate-400 font-bold">Gray</span> means the letter isn't part of today's
                            secret word.
                        </p>

                        <div class="pt-4 flex flex-wrap gap-4">
                            <button
                                class="flex items-center gap-2 bg-slate-900 dark:bg-white text-white dark:text-black px-8 py-3.5 rounded-2xl font-bold hover:scale-105 transition-transform active:scale-95">
                                {{ __('home.start_playing') }}
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>
                            <button
                                class="flex items-center gap-2 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-slate-200 dark:hover:bg-white/10 transition-colors">
                                {{ __('home.view_tutorial') }}
                            </button>
                        </div>
                    </div>
                </div>
            </article> --}}
{{-- 
            <div class="grid md:grid-cols-2 gap-8">
                <article
                    class="group p-8 rounded-[2rem] bg-gradient-to-b from-white to-slate-50 dark:from-white/5 dark:to-transparent border border-slate-200/60 dark:border-white/5 hover:border-green-500/30 transition-all duration-500">
                    <div
                        class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center shadow-sm mb-6 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-layer-group text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Custom Word Lengths</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
                        Why limit yourself to 5 letters? Challenge your vocabulary with puzzles ranging from quick
                        4-letter snacks to complex 11-letter brain busters.
                    </p>
                </article>

                <article
                    class="group p-8 rounded-[2rem] bg-gradient-to-b from-white to-slate-50 dark:from-white/5 dark:to-transparent border border-slate-200/60 dark:border-white/5 hover:border-blue-500/30 transition-all duration-500">
                    <div
                        class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center shadow-sm mb-6 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-language text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Global Dictionaries</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
                        Play Wordly in English, Español, Français, Deutsch, and more. A perfect way to practice a new
                        language while having fun.
                    </p>
                </article>

                <article
                    class="group p-8 rounded-[2rem] bg-gradient-to-b from-white to-slate-50 dark:from-white/5 dark:to-transparent border border-slate-200/60 dark:border-white/5 hover:border-yellow-500/30 transition-all duration-500">
                    <div
                        class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center shadow-sm mb-6 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-wand-magic-sparkles text-yellow-500"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Wordle Solver</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
                        Stuck on a tricky word? Our built-in solver helps you filter possibilities based on your current
                        clues to keep your streak alive.
                    </p>
                </article>

                <article
                    class="group p-8 rounded-[2rem] bg-gradient-to-b from-white to-slate-50 dark:from-white/5 dark:to-transparent border border-slate-200/60 dark:border-white/5 hover:border-purple-500/30 transition-all duration-500">
                    <div
                        class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center shadow-sm mb-6 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-share-nodes text-purple-500"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Create & Challenge</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
                        Generate your own custom Wordly puzzle link and challenge your friends. Can they guess your
                        secret word in under 6 tries?
                    </p>
                </article>
            </div> --}}

            <!-- Play Other Games Section -->
          @livewire('play-more-games')

            {{-- <section class="mt-16 grid gap-6 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-200/60 bg-white p-6 dark:border-white/5 dark:bg-white/5">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">How to Play</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        Guess the hidden word in six tries. Green means correct letter and position, yellow means correct letter in the wrong position, and gray means the letter is not in the word.
                    </p>
                </article>
                <article class="rounded-2xl border border-slate-200/60 bg-white p-6 dark:border-white/5 dark:bg-white/5">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Frequently Asked Questions</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        Wordly supports unlimited rounds, multiple languages, and adjustable difficulty settings from your dashboard.
                    </p>
                </article>
            </section> --}}

            @include('partials.game-content-section', [
                'game' => $defaultGame,
                'fallbackTitle' => 'About Wordle',
                'badge' => 'Wordle Guide',
            ])
        </div>
    </main>

@livewire('footer')

    @if (! empty($globalFooterCode))
        {!! $globalFooterCode !!}
    @endif

</body>

</html>
