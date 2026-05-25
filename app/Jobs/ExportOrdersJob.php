<?php

namespace App\Jobs;

use App\Exports\OrdersExport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\CleanOldExportFiles;

class ExportOrdersJob implements ShouldQueue
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
            Excel::store(new OrdersExport($this->filters), $this->filePath, 'public');

            // Cache file path for download
            Cache::put('order_export_' . $this->userId, $this->filePath, now()->addMinutes(10));

            // Dispatch cleanup job to delete the file after delay
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
