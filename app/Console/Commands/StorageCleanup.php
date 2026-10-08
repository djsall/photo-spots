<?php

namespace App\Console\Commands;

use App\Models\Spot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class StorageCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:storage-cleanup {--dry-run : Run the command without deleting any files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up storage from unused images';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY-RUN mode. No files will be deleted.');
        }

        Artisan::call('down');

        try {
            $usedFiles = $this->getUsedFileNames();

            $diskFiles = Storage::disk('public')->files();
            $diskFiles = $this->ignoreDotFiles($diskFiles);

            $unusedFiles = array_diff($diskFiles, $usedFiles);

            if (blank($unusedFiles)) {
                $this->info('No unused files found.');

                return Command::SUCCESS;
            }

            $this->info(count($unusedFiles).' unused file(s) found.');

            if ($dryRun) {
                $this->displayTable($unusedFiles);
            } else {
                $this->deleteFiles($unusedFiles);
            }

            return Command::SUCCESS;

        } finally {
            Artisan::call('up');
        }
    }

    private function getUsedFileNames(): array
    {
        return Spot::query()
            ->whereNotNull('images')
            ->pluck('images')
            ->filter()
            ->flatten()
            ->toArray();
    }

    private function ignoreDotFiles(array $files): array
    {
        return array_filter(
            $files,
            static fn (string $file): bool => ! str_starts_with(basename($file), '.')
        );
    }

    private function deleteFiles(array $files): void
    {
        Storage::disk('public')->delete($files);
        $this->info('Unused files deleted successfully.');
    }

    private function displayTable(array $files): void
    {
        $lines = array_map(
            static fn (string $file): array => [$file],
            $files
        );

        $this->table(['Files to delete'], $lines);
    }
}
