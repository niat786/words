<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogSlugRedirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->publiclyVisible()
            ->latest('published_at')
            ->latest('id')
            ->paginate(12);

        return view('blog.index', [
            'title' => 'Blog',
            'blogs' => $blogs,
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {
        $blog = Blog::query()
            ->publiclyVisible()
            ->where('slug', $slug)
            ->first();

        if ($blog === null) {
            $redirect = BlogSlugRedirect::query()
                ->where('old_slug', $slug)
                ->with('blog')
                ->first();

            if ($redirect !== null && $redirect->blog !== null && $this->isPubliclyVisible($redirect->blog)) {
                return redirect()->route('blog.show', ['slug' => $redirect->blog->slug], 301);
            }

            abort(404);
        }

        $canonicalUrl = $blog->canonical_url ?: route('blog.show', ['slug' => $blog->slug]);
        $translatedTitle = $blog->translated('title') ?: $blog->title;
        $translatedExcerpt = $blog->translated('excerpt') ?: $blog->excerpt;
        $translatedContent = $blog->translated('content') ?: $blog->content;
        $seoTitle = $translatedTitle;
        $seoDescription = $translatedExcerpt ?: Str::of(strip_tags($translatedContent))->squish()->limit(160)->toString();
        $featuredImageUrl = $this->resolveFeaturedImageUrl($blog->featured_image_path);
        $robotsDirectives = implode(', ', array_filter([
            $blog->robots_index ? 'index' : 'noindex',
            $blog->robots_follow ? 'follow' : 'nofollow',
        ]));

        return view('blog.show', [
            'title' => $translatedTitle,
            'blog' => $blog,
            'translatedTitle' => $translatedTitle,
            'translatedExcerpt' => $translatedExcerpt,
            'translatedContent' => $translatedContent,
            'featuredImageUrl' => $featuredImageUrl,
            'seoTitle' => $seoTitle,
            'seoDescription' => $seoDescription,
            'seoCanonicalUrl' => $canonicalUrl,
            'seoRobots' => $robotsDirectives,
            'seoOpenGraph' => [
                'title' => $blog->og_title ?: $seoTitle,
                'description' => $blog->og_description ?: $seoDescription,
                'type' => 'article',
                'url' => route('blog.show', ['slug' => $blog->slug]),
                'image' => $featuredImageUrl,
            ],
            'seoTwitter' => [
                'card' => $featuredImageUrl !== null ? 'summary_large_image' : 'summary',
                'title' => $blog->twitter_title ?: ($blog->og_title ?: $seoTitle),
                'description' => $blog->twitter_description ?: ($blog->og_description ?: $seoDescription),
                'image' => $featuredImageUrl,
            ],
            'seoJsonLd' => $this->jsonLdPayload($blog, $seoTitle, $seoDescription, $canonicalUrl, $featuredImageUrl),
        ]);
    }

    protected function isPubliclyVisible(Blog $blog): bool
    {
        if ($blog->status === 'published') {
            return $blog->published_at === null || $blog->published_at->lte(now());
        }

        if ($blog->status === 'scheduled') {
            return $blog->published_at !== null && $blog->published_at->lte(now());
        }

        return false;
    }

    protected function resolveFeaturedImageUrl(?string $featuredImagePath): ?string
    {
        if (! is_string($featuredImagePath) || $featuredImagePath === '') {
            return null;
        }

        if (Str::startsWith($featuredImagePath, ['http://', 'https://'])) {
            return $featuredImagePath;
        }

        return Storage::url($featuredImagePath);
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function jsonLdPayload(
        Blog $blog,
        string $seoTitle,
        string $seoDescription,
        string $canonicalUrl,
        ?string $featuredImageUrl,
    ): array {
        if ($this->isValidJson($blog->schema_markup_json)) {
            /** @var array<string, mixed>|list<mixed> $decoded */
            $decoded = json_decode((string) $blog->schema_markup_json, true, 512, JSON_THROW_ON_ERROR);

            if (array_is_list($decoded)) {
                return array_values(
                    array_filter(
                        $decoded,
                        fn (mixed $item): bool => is_array($item),
                    ),
                );
            }

            return [is_array($decoded) ? $decoded : []];
        }

        $schemaType = $blog->schema_type !== '' ? $blog->schema_type : 'BlogPosting';
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $schemaType,
            'headline' => $seoTitle,
            'description' => $seoDescription,
            'url' => $canonicalUrl,
            'datePublished' => $blog->published_at?->toAtomString(),
            'dateModified' => $blog->updated_at?->toAtomString(),
            'author' => [
                '@type' => 'Person',
                'name' => $blog->user?->name ?: config('app.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ];

        if ($featuredImageUrl !== null) {
            $schema['image'] = [$featuredImageUrl];
        }

        return [$schema];
    }

    protected function isValidJson(?string $value): bool
    {
        if (! is_string($value) || trim($value) === '') {
            return false;
        }

        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);

            return true;
        } catch (\JsonException) {
            return false;
        }
    }
}
