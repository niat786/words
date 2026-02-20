<?php

use App\Support\Seo\BlogSeoAnalyzer;
use Tests\TestCase;

uses(TestCase::class);

it('returns higher score for stronger seo input', function (): void {
    $analyzer = app(BlogSeoAnalyzer::class);

    $weak = $analyzer->analyze([
        'title' => 'Post',
        'slug' => 'post',
        'content' => '<p>Short text.</p>',
        'excerpt' => 'Short excerpt.',
        'status' => 'draft',
        'robots_index' => true,
        'robots_follow' => true,
    ]);

    $strong = $analyzer->analyze([
        'title' => 'Laravel Blog SEO Optimization Guide for Better Rankings',
        'seo_title' => 'Laravel SEO Guide: Optimize Blog Posts for Rankings',
        'slug' => 'laravel-seo-guide-blog-rankings',
        'focus_keyword' => 'laravel seo',
        'meta_description' => 'Learn practical Laravel SEO strategies for blog posts, including title optimization, keyword placement, metadata, and canonical setup.',
        'canonical_url' => 'https://wordle.test/blog/laravel-seo-guide-blog-rankings',
        'content' => '<h2>Laravel SEO strategy</h2><p>Laravel SEO matters for growth. This Laravel SEO guide explains practical implementation patterns and internal linking.</p><h3>Keyword placement</h3><p>Use laravel seo in title, meta description, and body while keeping natural language. Link to <a href="/blog/internal">internal resources</a> and <a href="https://laravel.com/docs">official docs</a>.</p>',
        'excerpt' => 'Laravel SEO implementation guide for production teams.',
        'featured_image_path' => 'blogs/featured-images/seo-guide.jpg',
        'featured_image_alt' => 'Laravel SEO guide cover image',
        'status' => 'published',
        'robots_index' => true,
        'robots_follow' => true,
    ]);

    expect($strong['score'])->toBeGreaterThan($weak['score']);
    expect($strong['grade'])->toBeIn(['good', 'ok', 'poor']);
    expect($strong)->toHaveKeys(['score', 'grade', 'checks', 'criticalIssues', 'metrics']);
});
