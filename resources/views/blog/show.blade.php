<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <main class="mx-auto w-full max-w-4xl px-6 py-12">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">&larr; Back to blog</a>

            <article class="mt-6 rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <header>
                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400">{{ strtoupper($blog->status) }}</p>
                    <h1 class="mt-2 text-4xl font-black tracking-tight">{{ $blog->title }}</h1>
                    <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ optional($blog->published_at)->format('M d, Y H:i') ?? 'Unscheduled' }}
                        @if ($blog->user)
                            - By {{ $blog->user->name }}
                        @endif
                    </p>
                </header>

                @if (filled($featuredImageUrl ?? null))
                    <img
                        src="{{ $featuredImageUrl }}"
                        alt="{{ $blog->featured_image_alt ?: $blog->title }}"
                        class="mt-8 w-full rounded-xl border border-zinc-200 object-cover dark:border-zinc-700"
                    >
                @endif

                <div class="prose prose-zinc mt-8 max-w-none dark:prose-invert">
                    {!! $blog->content !!}
                </div>
            </article>
        </main>
        @fluxScripts
    </body>
</html>
