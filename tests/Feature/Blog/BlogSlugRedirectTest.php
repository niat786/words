<?php

use App\Models\Blog;

it('creates slug redirect and responds with 301 when published slug changes', function (): void {
    $blog = Blog::factory()->published()->create([
        'slug' => 'old-seo-slug',
        'title' => 'SEO old slug post',
        'focus_keyword' => 'seo slug',
    ]);

    $blog->update([
        'slug' => 'new-seo-slug',
    ]);

    $this->assertDatabaseHas('blog_slug_redirects', [
        'blog_id' => $blog->id,
        'old_slug' => 'old-seo-slug',
    ]);

    $response = $this->get('/blog/old-seo-slug');

    $response->assertStatus(301);
    $response->assertRedirect(route('blog.show', ['slug' => 'new-seo-slug']));
});
