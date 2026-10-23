<?php

namespace App\Jobs;

use App\Exports\LeadsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\CleanOldExportFiles;


class ExportLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filters;
    protected $filePath;
    protected $userId;

    public function __construct($filters, $filePath, $userId)
    {
        $this->filters = $filters;
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            Excel::store(new LeadsExport($this->filters), $this->filePath, 'public');

            // Cache for later retrieval (valid for 60 mins)
            Cache::put('lead_export_' . $this->userId, $this->filePath, now()->addMinutes(60));
            
            // Dispatch cleanup job only if not on sync connection
            if (config('queue.default') !== 'sync') {
                CleanOldExportFiles::dispatch($this->filePath)
                    ->delay(now()->addMinutes((int) env('EXPORT_CLEANUP_EXPIRY_MINUTES', 60)));
            }
        } catch (\Throwable $th) {
            //throw $th;
        } finally{
             // Close all DB connections explicitly
            \DB::disconnect('mysql');
        }

    }
}

