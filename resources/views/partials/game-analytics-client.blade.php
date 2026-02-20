@php
    $analyticsContext = $analyticsContext ?? 'web';
@endphp

<script>
    (() => {
        const analyticsConfig = {
            storeUrl: @js(route('game-analytics.store')),
            syncUrl: @js(route('game-analytics.sync')),
            csrfToken: @js(csrf_token()),
            isAuthenticated: @js(auth()->check()),
            context: @js($analyticsContext),
        };

        if (window.WordlyAnalytics?.__isInitialized) {
            window.WordlyAnalytics.configure(analyticsConfig);
            window.WordlyAnalytics.syncQueue();

            return;
        }

        const STORAGE_KEY = 'wordly_game_analytics_queue_v1';
        const MAX_QUEUE_SIZE = 500;

        const toObject = (value) => {
            if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                return value;
            }

            return null;
        };

        const generateEventId = () => {
            if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
                return crypto.randomUUID();
            }

            return `event-${Date.now()}-${Math.random().toString(16).slice(2)}`;
        };

        const loadQueue = () => {
            try {
                const queue = JSON.parse(localStorage.getItem(STORAGE_KEY) ?? '[]');

                if (Array.isArray(queue)) {
                    return queue;
                }

                return [];
            } catch (_error) {
                return [];
            }
        };

        const saveQueue = (queue) => {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(queue.slice(-MAX_QUEUE_SIZE)));
        };

        const postJson = async (url, payload) => {
            return fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': analyticsConfig.csrfToken,
                },
                body: JSON.stringify(payload),
            });
        };

        const normalizeEvent = (payload) => {
            const normalized = {
                client_event_id: payload.client_event_id || generateEventId(),
                game_key: String(payload.game_key || '').trim(),
                event_type: String(payload.event_type || '').trim(),
                occurred_at: payload.occurred_at || new Date().toISOString(),
            };

            if (payload.status !== undefined && payload.status !== null && payload.status !== '') {
                normalized.status = String(payload.status);
            }

            if (Number.isInteger(payload.attempts)) {
                normalized.attempts = payload.attempts;
            }

            if (Number.isInteger(payload.word_length)) {
                normalized.word_length = payload.word_length;
            }

            if (Number.isInteger(payload.score)) {
                normalized.score = payload.score;
            }

            if (Number.isInteger(payload.duration_seconds)) {
                normalized.duration_seconds = payload.duration_seconds;
            }

            const metadata = toObject(payload.metadata) ?? {};
            normalized.metadata = {
                ...metadata,
                context: analyticsConfig.context,
            };

            return normalized;
        };

        const analytics = {
            __isInitialized: true,
            configure(config) {
                analyticsConfig.isAuthenticated = Boolean(config.isAuthenticated);
                analyticsConfig.storeUrl = config.storeUrl || analyticsConfig.storeUrl;
                analyticsConfig.syncUrl = config.syncUrl || analyticsConfig.syncUrl;
                analyticsConfig.csrfToken = config.csrfToken || analyticsConfig.csrfToken;
                analyticsConfig.context = config.context || analyticsConfig.context;
            },
            enqueue(eventPayload) {
                const queue = loadQueue();
                queue.push(normalizeEvent(eventPayload));
                saveQueue(queue);
            },
            async track(eventPayload) {
                const event = normalizeEvent(eventPayload);

                if (!event.game_key || !event.event_type) {
                    return false;
                }

                if (!analyticsConfig.isAuthenticated) {
                    analytics.enqueue(event);

                    return true;
                }

                try {
                    const response = await postJson(analyticsConfig.storeUrl, event);

                    if (!response.ok) {
                        throw new Error('Failed to save analytics event');
                    }

                    return true;
                } catch (_error) {
                    analytics.enqueue(event);

                    return false;
                }
            },
            async syncQueue() {
                if (!analyticsConfig.isAuthenticated) {
                    return;
                }

                const queue = loadQueue();

                if (queue.length === 0) {
                    return;
                }

                try {
                    const response = await postJson(analyticsConfig.syncUrl, {
                        events: queue,
                    });

                    if (!response.ok) {
                        return;
                    }

                    saveQueue([]);
                } catch (_error) {
                }
            },
        };

        window.WordlyAnalytics = analytics;

        if (analyticsConfig.isAuthenticated) {
            setTimeout(() => {
                analytics.syncQueue();
            }, 50);
        }

        window.addEventListener('online', () => {
            analytics.syncQueue();
        });
    })();
</script>
