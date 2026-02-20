<?php

use App\Models\Blog;

it('renders canonical, robots, og, twitter, and json-ld metadata on blog page', function (): void {
    $blog = Blog::factory()->published()->create([
        'slug' => 'seo-meta-showcase',
        'title' => 'SEO Meta Showcase Title',
        'excerpt' => 'SEO metadata rendering test description for canonical and social tags.',
        'focus_keyword' => 'seo metadata',
        'canonical_url' => 'https://wordle.test/blog/seo-meta-showcase',
        'robots_index' => true,
        'robots_follow' => true,
        'og_title' => 'OG SEO Meta Showcase',
        'og_description' => 'OG description for SEO metadata page.',
        'twitter_title' => 'Twitter SEO Meta Showcase',
        'twitter_description' => 'Twitter description for SEO metadata page.',
    ]);

    $response = $this->get(route('blog.show', ['slug' => $blog->slug]));

    $response->assertOk();
    $response->assertSee('<title>SEO Meta Showcase Title</title>', false);
    $response->assertSee('name="description" content="SEO metadata rendering test description for canonical and social tags."', false);
    $response->assertSee('rel="canonical" href="https://wordle.test/blog/seo-meta-showcase"', false);
    $response->assertSee('name="robots" content="index, follow"', false);
    $response->assertSee('property="og:title" content="OG SEO Meta Showcase"', false);
    $response->assertSee('name="twitter:title" content="Twitter SEO Meta Showcase"', false);
    $response->assertSee('application/ld+json', false);
    $response->assertSee('"@type":"BlogPosting"', false);
});
