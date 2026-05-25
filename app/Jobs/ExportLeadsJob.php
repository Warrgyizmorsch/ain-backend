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

            // Cache for later retrieval (valid for 10 mins)
            Cache::put('lead_export_' . $this->userId, $this->filePath, now()->addMinutes(10));
            
            // 2. Dispatch cleanup job (delayed by given minutes)
            // Only send filePath to be deleted — it's already available
            CleanOldExportFiles::dispatch($this->filePath)
                ->delay(now()->addMinutes(env('EXPORT_CLEANUP_EXPIRY_MINUTES', 2)));
        } catch (\Throwable $th) {
            //throw $th;
        } finally{
             // Close all DB connections explicitly
            \DB::disconnect('mysql');
        }

    }
}

