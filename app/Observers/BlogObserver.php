<?php

namespace App\Observers;

use App\Models\Blog;
use App\Models\BlogSlugRedirect;
use App\Support\Seo\BlogSeoAnalyzer;

class BlogObserver
{
    public function __construct(
        protected BlogSeoAnalyzer $analyzer
    ) {
    }

    public function saving(Blog $blog): void
    {
        $analysis = $this->analyzer->analyze([
            'title' => $blog->title,
            'seo_title' => $blog->seo_title,
            'meta_description' => $blog->meta_description,
            'focus_keyword' => $blog->focus_keyword,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'excerpt' => $blog->excerpt,
            'featured_image_path' => $blog->featured_image_path,
            'featured_image_alt' => $blog->featured_image_alt,
            'status' => $blog->status,
            'canonical_url' => $blog->canonical_url,
            'robots_index' => $blog->robots_index,
            'robots_follow' => $blog->robots_follow,
        ]);

        $blog->seo_score = $analysis['score'];
        $blog->seo_grade = $analysis['grade'];
    }

    public function updated(Blog $blog): void
    {
        if (! $blog->wasChanged('slug')) {
            return;
        }

        $oldSlug = (string) $blog->getOriginal('slug');
        $oldStatus = (string) $blog->getOriginal('status');

        if ($oldSlug === '' || ! in_array($oldStatus, ['published', 'scheduled'], true)) {
            return;
        }

        BlogSlugRedirect::query()->updateOrCreate(
            ['old_slug' => $oldSlug],
            ['blog_id' => $blog->getKey()],
        );
    }
}
