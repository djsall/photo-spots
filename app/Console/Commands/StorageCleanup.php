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
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('Running in DRY-RUN mode. No files will be deleted.');
        }

        Artisan::call('down');

        try {
            $dbFiles = Spot::query()->whereNotNull('images')->pluck('images')
                ->filter()
                ->flatten()
                ->toArray();

            $allDiskFiles = Storage::disk('public')->allFiles();

            $allDiskFiles = array_filter($allDiskFiles, function ($file) {
                return ! str_starts_with(basename($file), '.');
            });

            $filesToDelete = array_diff($allDiskFiles, $dbFiles);

            if (blank($filesToDelete)) {
                $this->info('No unused files found.');
            } else {
                $this->info(count($filesToDelete).' unused file(s) found.');

                if ($isDryRun) {
                    $this->table(['Files to delete'], array_map(fn ($f) => [$f], $filesToDelete));
                } else {
                    Storage::disk('public')->delete($filesToDelete);
                    $this->info('Unused files deleted successfully.');
                }
            }
        } finally {
            Artisan::call('up');
        }

        return Command::SUCCESS;
    }
}
