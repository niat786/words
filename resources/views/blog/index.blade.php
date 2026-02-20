<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <main class="mx-auto w-full max-w-5xl px-6 py-12">
            <header class="mb-10">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Insights</p>
                <h1 class="mt-2 text-4xl font-black tracking-tight">Blog</h1>
            </header>

            <section class="grid gap-6 md:grid-cols-2">
                @forelse ($blogs as $blog)
                    <article class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ optional($blog->published_at)->format('M d, Y') ?? 'Draft' }}</p>
                        <h2 class="mt-2 text-2xl font-bold leading-tight">
                            <a href="{{ route('blog.show', ['slug' => $blog->slug]) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                {{ $blog->title }}
                            </a>
                        </h2>
                        @if (filled($blog->excerpt))
                            <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $blog->excerpt }}</p>
                        @endif
                        <div class="mt-4 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                            <span>SEO score: {{ $blog->seo_score ?? 'N/A' }}</span>
                            <span class="uppercase tracking-wide">{{ $blog->seo_grade ?? 'unrated' }}</span>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-zinc-300 bg-white p-8 text-sm text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 md:col-span-2">
                        No public blog posts are available yet.
                    </div>
                @endforelse
            </section>

            <div class="mt-8">
                {{ $blogs->links() }}
            </div>
        </main>
        @fluxScripts
    </body>
</html>
