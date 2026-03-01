<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CleanGameIconsCommand extends Command
{
    protected $signature = 'games:clean-icons
        {--days=2 : Delete only files older than this many days}
        {--dry-run : Preview files without deleting them}';

    protected $description = 'Remove orphaned files from storage/app/public/games/icons.';

    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $dryRun = (bool) $this->option('dry-run');
        $cutoffTimestamp = now()->subDays($days)->getTimestamp();
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles('games/icons');

        if ($allFiles === []) {
            $this->info('No files found in storage/app/public/games/icons.');

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

        $this->line('Total files scanned: '.number_format(count($allFiles)));
        $this->line('Referenced files: '.number_format(count($referencedPaths)));
        $this->line('Orphaned files: '.number_format(count($orphanedPaths)));

        if ($orphanedPaths === []) {
            $this->info('No orphaned game icons found.');

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

        $this->info("Deleted {$deletedCount} orphaned icon file(s).");

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    protected function collectReferencedPaths(): array
    {
        return Game::query()
            ->pluck('icon_path')
            ->map(fn (mixed $iconPath): ?string => Game::normalizeIconStoragePath(is_string($iconPath) ? $iconPath : null))
            ->filter(fn (?string $iconPath): bool => $iconPath !== null)
            ->unique()
            ->values()
            ->all();
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
