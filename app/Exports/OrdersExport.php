<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterChunk;
use Maatwebsite\Excel\Concerns\WithEvents;

class OrdersExport implements FromQuery, WithChunkReading, WithMapping, WithHeadings, ShouldQueue, WithBatchInserts, WithEvents
{
    protected $filters;
    protected $columns;

    // Default export columns
    protected $defaultColumns = [
        'order_id', 'customer_name', 'customer_email', 'customer_country_code', 'customer_phone',
        'order_date', 'deadline', 'project_title', 'status', 'pages', 'price','received', 'due', 'writer', 'other', 
    ];

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->columns = !empty($filters['selected_columns']) ? $filters['selected_columns'] : $this->defaultColumns;
    }

    public function query()
    {
        // $query = Order::with('user')->where('uid', '!=', 0);
        $query = DB::table('orders')
            ->select(
                'orders.*',
                'users.name as customer_name',
                'users.email as customer_email',
                'users.countrycode as customer_country_code',
                'users.mobile_no as customer_phone'
            )
            ->leftJoin('users', 'users.id', '=', 'orders.uid')
            ->where('orders.uid', '!=', 0);

        $filters = $this->filters;

        // Month-based filtering (special logic)
        if (!empty($filters['month'])) {
            try {
                // Ensure 'YYYY-MM' format is parsed correctly
                [$year, $month] = explode('-', $filters['month']);
                $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endOfMonth   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

                // Step 1: Get all UIDs with orders in this month
                $uidsInMonth = DB::table('orders')
                    ->whereBetween('order_date', [$startOfMonth, $endOfMonth])
                    ->pluck('uid')
                    ->unique();

                if ($uidsInMonth->isEmpty()) {
                    // No matching UIDs, force empty result
                    $query->whereRaw('0 = 1');
                    return;
                }

                // Step 2: Get UIDs that have newer orders after this month
                $uidsWithNewerOrders = DB::table('orders')
                    ->whereIn('uid', $uidsInMonth)
                    ->where('order_date', '>', $endOfMonth)
                    ->pluck('uid')
                    ->unique();

                // Step 3: Only keep UIDs that do NOT have newer orders
                $validUIDs = $uidsInMonth->diff($uidsWithNewerOrders);

                if ($validUIDs->isEmpty()) {
                    $query->whereRaw('0 = 1');
                    return;
                }

                // Step 4: Filter $query to include only the latest order per UID for that month
                $query->whereIn('orders.id', function ($subQuery) use ($startOfMonth, $endOfMonth, $validUIDs) {
                    $subQuery->selectRaw('MAX(id)')
                        ->from('orders')
                        ->whereBetween('order_date', [$startOfMonth, $endOfMonth])
                        ->whereIn('uid', $validUIDs)
                        ->groupBy('uid');
                });

            } catch (\Exception $e) {
                // If month format is invalid, return no results
                $query->whereRaw('0 = 1');
            }
        }

        // Search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('order_id', 'like', '%' . $filters['search'] . '%')
                ->orWhere('title', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Basic filters
        if (!empty($filters['uid'])) {
            $query->where('uid', $filters['uid']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['status'])) {
            $query->where('projectstatus', $filters['status']);
        }

        if (!empty($filters['module_code'])) {
            $query->where('module_code', 'like', '%' . $filters['module_code'] . '%');
        }

        if (!empty($filters['paper_type'])) {
            $query->where('typeofpaper', $filters['paper_type']);
        }

        if (!empty($filters['college'])) {
            $query->where('college_name', $filters['college']);
        }

        // Writer
        if (!empty($filters['writer'])) {
            if ($filters['writer'] === 'team 13') {
                $query->where('admin_id', 8392);
            } elseif ($filters['writer'] === 'Not Assign') {
                $query->where(function ($q) {
                    $q->whereNull('writer_name')->orWhere('writer_name', '');
                });
            } else {
                $query->where('writer_name', 'like', $filters['writer']);
            }
        }

        // Writer TL
        if (!empty($filters['WriterTL'])) {
            $query->where('wid', $filters['WriterTL']);
        }

        // SubWriter (external relation)
        if (!empty($filters['SubWriter'])) {
            $orderIds = \App\Models\multipleswiter::where('user_id', $filters['SubWriter'])->pluck('order_id');
            $query->whereIn('id', $orderIds);
        }

        // Extra logic
        switch ($filters['extra'] ?? null) {
            case 'tech':
                $query->where('tech', 1);
                break;
            case 'resit':
                $query->where('resit', 'on');
                break;
            case 'failedjob':
                $query->where('is_fail', 1);
                break;
            case '1':
                $query->where('services', 'First Class Work');
                break;
        }

        // Date filtering
        $from = $filters['fromDate'] ?? null;
        $to = $filters['toDate'] ?? null;
        $dateField = $filters['dateStatus'] ?? null;

        if ($from && $to && $dateField) {
            if ($dateField === 'draft_date') {
                $query->whereBetween($dateField, [$from, $to])->where('draftrequired', 'y');
            } else {
                $query->whereBetween($dateField, [$from, $to]);
            }
        } elseif ($from && $to) {
            $query->whereBetween('order_date', [$from, $to]);
        } elseif ($from) {
            $query->where('order_date', $from);
        } elseif ($dateField) {
            $query->whereDate('order_date', Carbon::today());
        }

        return $query->orderByDesc('id');
    }

    public function map($order): array
    {
        $row = [];

        foreach ($this->columns as $column) {
            switch ($column) {
                case 'order_id':
                    $row[] = $order->order_id;
                    break;

                case 'customer_name':
                    $row[] = $order->customer_name ?? '';
                    break;

                case 'customer_email':
                    $row[] = $order->customer_email ?? '';
                    break;

                case 'customer_country_code':
                    $row[] = $order->customer_country_code ?? '';
                    break;

                case 'customer_phone':
                    $row[] = $order->customer_phone ?? '';
                    break;

                case 'order_date':
                    $row[] = $this->formatDate($order->created_at);
                    break;

                case 'deadline':
                    $row[] = $this->formatDate($order->delivery_date);
                    break;

                case 'project_title':
                    $row[] = $order->title ?? '';
                    break;
                
                case 'status':
                    $row[] = $order->projectstatus ?? '';
                    break;

                case 'pages':
                    $row[] = $order->pages ?? '';
                    break;

                case 'price':
                    $row[] = $order->amount ?? '';
                    break;

                case 'received':
                    $row[] = $order->received_amount ?? '';
                    break;

                case 'due':
                    if (is_numeric($order->amount) && is_numeric($order->received_amount)) {
                        $row[] = $order->amount - $order->received_amount;
                    } else {
                        $row[] = '';
                    }
                    break;
                
                case 'writer':
                    $row[] = $order->writer_name ?? '';
                    break;
                
                case 'other':
                    $convertedBy = $order->l_converted_by ?? 'N/A';

                    if ($order->failed_by) {
                        $failedBy = "Failed By: {$order->failed_by} at {$order->failed_at}";
                    } else {
                        $failedBy = "Failed By: N/A";
                    }

                    $row[] = "Convert By: {$convertedBy}, \n{$failedBy}";
                    break;

                

                default:
                    $row[] = '';
            }
        }

        return $row;
    }

    public function headings(): array
    {
        $labels = [
            'order_id' => 'Order ID',
            'customer_name' => 'Name',
            'customer_email' => 'Email',
            'customer_country_code' => 'Country Code',
            'customer_phone' => 'Phone',
            'order_date' => 'Order Date',
            'deadline' => 'Deadline',
            'project_title' => 'Project Title',
            'status' => 'Status',
            'pages' => 'Words',
            'price' => 'Price',
            'received' => 'Received',
            'due' => 'Due',
            'writer' => 'Writer',
            'other' => 'Other',
        ];

        return array_map(fn($col) => $labels[$col] ?? ucfirst(str_replace('_', ' ', $col)), $this->columns);
    }

    public static function afterChunk(AfterChunk $event)
    {
        DB::disconnect('mysql'); // free connection after chunk
    }

    public function registerEvents(): array
    {
        return [
            AfterChunk::class => [self::class, 'afterChunk'],
        ];
    }
    public function chunkSize(): int
    {
        return 500; // adjust as needed
    }

    public function batchSize(): int
    {
        return 500;
    }

    private function formatDate($date): string
    {
        try {
            return !empty($date) ? Carbon::parse($date)->format('d M Y') : '';
        } catch (\Exception $e) {
            return '';
        }
    }
}
