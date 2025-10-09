<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateToR2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-to-r2
        {--from=public : Source filesystem disk}
        {--to=r2 : Destination filesystem disk}
        {--prefix= : Comma-separated folder prefixes to migrate (default: settings,experiences,educations,projects,certifications)}
        {--all : Migrate all files from the source disk}
        {--dry-run : Show what will happen without writing}
        {--delete-local : Delete the local file after successful upload}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate files from a source disk (default: public) to Cloudflare R2 (S3-compatible)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromDiskName = (string) $this->option('from');
        $toDiskName = (string) $this->option('to');
        $dryRun = (bool) $this->option('dry-run');
        $deleteLocal = (bool) $this->option('delete-local');
        $migrateAll = (bool) $this->option('all');

        $prefixOpt = $this->option('prefix');
        $defaultPrefixes = ['settings', 'experiences', 'educations', 'projects', 'certifications'];
        $prefixes = $migrateAll
            ? ['']
            : ($prefixOpt ? array_filter(array_map('trim', explode(',', (string) $prefixOpt))) : $defaultPrefixes);

        $from = Storage::disk($fromDiskName);
        $to = Storage::disk($toDiskName);

        $this->info("Migrating files from disk '{$fromDiskName}' to '{$toDiskName}'");
        if ($dryRun) {
            $this->warn('Dry run mode enabled. No changes will be made.');
        }

        $files = [];
        foreach ($prefixes as $prefix) {
            // If prefix is empty string, fetch all files
            $files = array_merge($files, $from->allFiles($prefix));
        }
        $files = array_values(array_unique($files));
        // Exclude dotfiles anywhere in the path, e.g., .gitignore and hidden files
        $files = array_values(array_filter($files, function ($p) {
            if (!is_string($p) || $p === '') return false;
            return !preg_match('/(^|\/)\./', $p);
        }));

        if (empty($files)) {
            $this->info('No files found to migrate.');
            return self::SUCCESS;
        }

        $this->info('Found '.count($files).' files to process.');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($files as $path) {
            try {
                if ($dryRun) {
                    // Just simulate
                    $migrated++;
                    $bar->advance();
                    continue;
                }

                // Non-dry-run: check destination existence (best-effort)
                try {
                    if ($to->exists($path)) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }
                } catch (\Throwable $e) {
                    // If existence check fails, proceed to attempt write
                }

                $stream = $from->readStream($path);
                if ($stream === false) {
                    throw new \RuntimeException("Unable to read stream for '{$path}' from '{$fromDiskName}'.");
                }

                // Write stream to destination with public visibility (R2)
                $to->writeStream($path, $stream, ['visibility' => 'public']);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                if (! $to->exists($path)) {
                    throw new \RuntimeException("After write, file missing at destination for '{$path}'.");
                }

                if ($deleteLocal) {
                    $from->delete($path);
                }

                $migrated++;
                $bar->advance();
            } catch (Throwable $e) {
                $errors++;
                $bar->advance();
                $this->error("\nFailed: {$path} — ".$e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->line('Summary:');
        $this->line(' - Migrated: '.$migrated);
        $this->line(' - Skipped (already exists): '.$skipped);
        $this->line(' - Errors: '.$errors);

        if ($dryRun) {
            $this->warn('Dry run completed. No changes were written.');
        }

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }
}
