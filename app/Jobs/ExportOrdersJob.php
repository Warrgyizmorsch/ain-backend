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
use Illuminate\Support\Facades\Log;

class ExportOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filters;
    protected $filePath;
    protected $userId;
    protected $exportId;

    public function __construct($filters, $filePath, $userId, $exportId)
    {
        $this->filters = $filters;
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->exportId = $exportId;
    }

    public function handle()
    {
        $statusKey = 'order_export_status_' . $this->userId;

        try {
            Cache::put($statusKey, [
                'export_id' => $this->exportId,
                'status' => 'processing',
                'progress' => 3,
                'message' => 'Preparing order data...',
            ], now()->addMinutes(30));

            $countExport = new OrdersExport($this->filters);
            $totalRows = (clone $countExport->query())->count();

            Cache::put($statusKey, [
                'export_id' => $this->exportId,
                'status' => 'processing',
                'progress' => 8,
                'processed' => 0,
                'total' => $totalRows,
                'message' => $totalRows > 0 ? 'Exporting orders...' : 'Creating empty export...',
            ], now()->addMinutes(30));

            $export = new OrdersExport($this->filters, $statusKey, $this->exportId, $totalRows);
            Excel::store($export, $this->filePath, 'public');

            Cache::put($statusKey, [
                'export_id' => $this->exportId,
                'status' => 'ready',
                'progress' => 100,
                'processed' => $totalRows,
                'total' => $totalRows,
                'message' => 'Export ready to download.',
                'file_path' => $this->filePath,
            ], now()->addMinutes(10));

            // Dispatch cleanup job to delete the file after delay
            CleanOldExportFiles::dispatch($this->filePath)
                ->delay(now()->addMinutes(env('EXPORT_CLEANUP_EXPIRY_MINUTES', 2)));
        } catch (\Throwable $th) {
            Log::error('Order export failed', [
                'user_id' => $this->userId,
                'export_id' => $this->exportId,
                'error' => $th->getMessage(),
            ]);

            Cache::put($statusKey, [
                'export_id' => $this->exportId,
                'status' => 'failed',
                'progress' => 0,
                'message' => 'Export failed. Please try again.',
            ], now()->addMinutes(10));
        } finally{
             // Close all DB connections explicitly
            \DB::disconnect('mysql');
        }
        
    }
}
