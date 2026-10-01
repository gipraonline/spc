<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * One-time move of already-uploaded files out of web-reachable folders into
 * private storage (storage/app/private). Relative paths / file names are kept,
 * so no database changes are needed.
 *
 *   storage/app/public/documents/...   -> storage/app/private/documents/...
 *   public/uploads/payment_images/...  -> storage/app/private/sales/payment_images/...
 *   public/uploads/booklet_images/...  -> storage/app/private/sales/booklet_images/...
 */
class SecureLegacyFiles extends Command
{
    protected $signature = 'files:secure-legacy {--dry-run : List what would be moved without moving it}';

    protected $description = 'Move HR documents and sales proof images from public locations to private storage';

    public function handle(): int
    {
        $private = storage_path('app/private');

        $moves = [
            storage_path('app/public/documents') => $private.'/documents',
            public_path('uploads/payment_images') => $private.'/sales/payment_images',
            public_path('uploads/booklet_images') => $private.'/sales/booklet_images',
        ];

        $moved = 0;

        foreach ($moves as $from => $to) {
            if (! is_dir($from)) {
                continue;
            }

            foreach (File::allFiles($from) as $file) {
                $relative = $file->getRelativePathname();
                $target = $to.'/'.$relative;

                if ($this->option('dry-run')) {
                    $this->line("would move {$file->getPathname()} -> {$target}");
                    $moved++;

                    continue;
                }

                File::ensureDirectoryExists(dirname($target));

                if (File::exists($target)) {
                    $this->warn("skipped (target exists): {$target}");

                    continue;
                }

                File::move($file->getPathname(), $target);
                $moved++;
            }
        }

        $this->info(($this->option('dry-run') ? 'Would move ' : 'Moved ').$moved.' file(s).');

        return self::SUCCESS;
    }
}
