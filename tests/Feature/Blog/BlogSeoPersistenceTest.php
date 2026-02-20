<?php

use App\Models\Blog;

it('persists seo score and grade when creating and updating blogs', function (): void {
    $blog = Blog::factory()->create([
        'title' => 'Word game strategy article',
        'slug' => 'word-game-strategy-article',
        'focus_keyword' => 'word game strategy',
        'meta_description' => 'Detailed word game strategy techniques with examples and practical guidance for daily puzzle players.',
        'content' => '<h2>Word game strategy</h2><p>Word game strategy improves solving speed and confidence for daily puzzle sessions with pattern recognition and letter positioning.</p>',
        'status' => 'published',
        'published_at' => now()->subMinutes(30),
    ]);

    $blog->refresh();

    expect($blog->seo_score)->not->toBeNull();
    expect($blog->seo_grade)->toBeIn(['good', 'ok', 'poor']);

    $initialScore = $blog->seo_score;

    $blog->update([
        'content' => '<p>tiny</p>',
        'meta_description' => 'short',
        'focus_keyword' => '',
    ]);

    $blog->refresh();

    expect($blog->seo_score)->not->toBeNull();
    expect($blog->seo_grade)->toBeIn(['good', 'ok', 'poor']);
    expect($blog->seo_score)->not->toBe($initialScore);
});
