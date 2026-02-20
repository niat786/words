<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

it('deletes orphaned private blog assets while preserving referenced and recent files', function (): void {
    Storage::fake('local');

    Storage::disk('local')->put('blogs/featured-images/featured.jpg', 'featured');
    Storage::disk('local')->put('blogs/content/inline.png', 'inline');
    Storage::disk('local')->put('blogs/orphans/orphan-old.png', 'old');
    Storage::disk('local')->put('blogs/orphans/orphan-recent.png', 'recent');

    touch(Storage::disk('local')->path('blogs/orphans/orphan-old.png'), now()->subDays(7)->getTimestamp());
    touch(Storage::disk('local')->path('blogs/orphans/orphan-recent.png'), now()->subHours(3)->getTimestamp());

    Blog::factory()->create([
        'featured_image_path' => 'blogs/featured-images/featured.jpg',
        'content' => '<p><img src="/storage/blogs/content/inline.png" alt="Inline"></p>',
    ]);

    Artisan::call('blogs:clean-assets', [
        '--days' => 2,
    ]);

    Storage::disk('local')->assertExists('blogs/featured-images/featured.jpg');
    Storage::disk('local')->assertExists('blogs/content/inline.png');
    Storage::disk('local')->assertExists('blogs/orphans/orphan-recent.png');
    Storage::disk('local')->assertMissing('blogs/orphans/orphan-old.png');
});

it('does not delete files in dry run mode', function (): void {
    Storage::fake('local');

    Storage::disk('local')->put('blogs/orphans/dry-run-orphan.png', 'orphan');
    touch(Storage::disk('local')->path('blogs/orphans/dry-run-orphan.png'), now()->subDays(4)->getTimestamp());

    Artisan::call('blogs:clean-assets', [
        '--days' => 0,
        '--dry-run' => true,
    ]);

    Storage::disk('local')->assertExists('blogs/orphans/dry-run-orphan.png');
});
