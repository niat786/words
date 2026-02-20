<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CleanBlogAssetsCommand extends Command
{
    protected $signature = 'blogs:clean-assets
        {--days=2 : Delete only files older than this many days}
        {--dry-run : Preview files without deleting them}';

    protected $description = 'Remove orphaned files from storage/app/private/blogs.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $dryRun = (bool) $this->option('dry-run');
        $cutoffTimestamp = now()->subDays($days)->getTimestamp();
        $disk = Storage::disk('local');
        $allFiles = $disk->allFiles('blogs');

        if ($allFiles === []) {
            $this->info('No files found in storage/app/private/blogs.');

            return self::SUCCESS;
        }

        $referencedPaths = $this->collectReferencedPaths();
        $referencedLookup = array_fill_keys($referencedPaths, true);
        $orphanedPaths = [];

        foreach ($allFiles as $filePath) {
            if (isset($referencedLookup[$filePath])) {
                continue;
            }

            if (! $this->isOlderThan($disk, $filePath, $cutoffTimestamp)) {
                continue;
            }

            $orphanedPaths[] = $filePath;
        }

        $this->line('Total files scanned: ' . number_format(count($allFiles)));
        $this->line('Referenced files: ' . number_format(count($referencedPaths)));
        $this->line('Orphaned files: ' . number_format(count($orphanedPaths)));

        if ($orphanedPaths === []) {
            $this->info('No orphaned blog assets found.');

            return self::SUCCESS;
        }

        foreach (array_slice($orphanedPaths, 0, 20) as $orphanedPath) {
            $this->line(" - {$orphanedPath}");
        }

        if (count($orphanedPaths) > 20) {
            $remaining = count($orphanedPaths) - 20;
            $this->line(" ... and {$remaining} more");
        }

        if ($dryRun) {
            $this->warn('Dry run enabled: no files were deleted.');

            return self::SUCCESS;
        }

        $deletedCount = 0;

        foreach ($orphanedPaths as $orphanedPath) {
            if ($disk->delete($orphanedPath)) {
                $deletedCount++;
            }
        }

        $this->info("Deleted {$deletedCount} orphaned file(s).");

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    protected function collectReferencedPaths(): array
    {
        $paths = [];

        Blog::query()
            ->withTrashed()
            ->select(['id', 'featured_image_path', 'content'])
            ->chunkById(200, function ($blogs) use (&$paths): void {
                foreach ($blogs as $blog) {
                    $featuredPath = $this->normalizeBlogAssetPath((string) $blog->featured_image_path);

                    if ($featuredPath !== null) {
                        $paths[$featuredPath] = true;
                    }

                    foreach ($this->extractBlogAssetPathsFromContent((string) $blog->content) as $contentPath) {
                        $paths[$contentPath] = true;
                    }
                }
            });

        return array_keys($paths);
    }

    /**
     * @return list<string>
     */
    protected function extractBlogAssetPathsFromContent(string $content): array
    {
        if (trim($content) === '') {
            return [];
        }

        $paths = [];

        preg_match_all('/(?:src|href)\s*=\s*["\']([^"\']+)["\']/i', $content, $attributeMatches);

        foreach ($attributeMatches[1] ?? [] as $match) {
            $normalizedPath = $this->normalizeBlogAssetPath((string) $match);

            if ($normalizedPath !== null) {
                $paths[$normalizedPath] = true;
            }
        }

        preg_match_all('/blogs\/[A-Za-z0-9_\-\/\.]+/', $content, $directMatches);

        foreach ($directMatches[0] ?? [] as $match) {
            $normalizedPath = $this->normalizeBlogAssetPath((string) $match);

            if ($normalizedPath !== null) {
                $paths[$normalizedPath] = true;
            }
        }

        return array_keys($paths);
    }

    protected function normalizeBlogAssetPath(string $path): ?string
    {
        $normalizedPath = html_entity_decode(trim($path), ENT_QUOTES | ENT_HTML5);

        if ($normalizedPath === '') {
            return null;
        }

        $normalizedPath = urldecode($normalizedPath);

        if (Str::startsWith($normalizedPath, ['http://', 'https://'])) {
            $parsedPath = parse_url($normalizedPath, PHP_URL_PATH);

            if (! is_string($parsedPath)) {
                return null;
            }

            $normalizedPath = $parsedPath;
        }

        $normalizedPath = preg_replace('/[#?].*$/', '', $normalizedPath) ?? $normalizedPath;
        $normalizedPath = str_replace('\\', '/', $normalizedPath);
        $normalizedPath = ltrim($normalizedPath, '/');

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/');
        }

        if (! Str::contains($normalizedPath, 'blogs/')) {
            return null;
        }

        $normalizedPath = 'blogs/' . Str::after($normalizedPath, 'blogs/');
        $normalizedPath = preg_replace('#/+#', '/', $normalizedPath) ?? $normalizedPath;

        return Str::startsWith($normalizedPath, 'blogs/')
            ? $normalizedPath
            : null;
    }

    protected function isOlderThan(FilesystemAdapter $disk, string $filePath, int $cutoffTimestamp): bool
    {
        try {
            return $disk->lastModified($filePath) <= $cutoffTimestamp;
        } catch (Throwable) {
            return false;
        }
    }
}
