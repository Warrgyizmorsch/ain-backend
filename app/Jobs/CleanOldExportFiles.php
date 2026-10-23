<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CleanOldExportFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath = null)
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        // 1. If queue is sync, delay is ignored by Laravel and executes immediately.
        // Never delete the active export file immediately on sync queue.
        $isSync = config('queue.default') === 'sync' || $this->connection === 'sync';

        if (!$isSync && !empty($this->filePath) && Storage::disk('public')->exists($this->filePath)) {
            $lastModified = Storage::disk('public')->lastModified($this->filePath);
            // Only delete if the file is at least 30 minutes old
            if (time() - $lastModified >= 1800) {
                Storage::disk('public')->delete($this->filePath);
            }
        }

        // 2. Housekeeping: purge any stale export files older than 2 hours
        self::cleanupExpired(7200);
    }

    public static function cleanupExpired(int $olderThanSeconds = 7200): void
    {
        try {
            $files = Storage::disk('public')->files('exports');
            $now = time();
            foreach ($files as $file) {
                if ($now - Storage::disk('public')->lastModified($file) > $olderThanSeconds) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore cleanup errors
        }
    }
}
