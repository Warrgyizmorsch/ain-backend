<?php

namespace App\Exports;

use App\Models\Leads;
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

class LeadsExport implements FromQuery, WithChunkReading, WithMapping, WithHeadings, ShouldQueue, WithBatchInserts, WithEvents
{
    protected $filters;
    protected $columns;

    // Default export columns
    protected $defaultColumns = [
        'order_id', 'name', 'customer_country_code', 'customer_phone', 'email', 'order_date',
        'project_title', 'pages', 'price', 'deadline',
    ];

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->columns = !empty($filters['selected_columns']) ? $filters['selected_columns'] : $this->defaultColumns;
    }

    public function query()
    {        
        $query = DB::table('leads')
            ->leftJoin('users', 'leads.emp_id', '=', 'users.id')
            ->select(
                'leads.id',
                'leads.order_id',
                'leads.project_title',
                'leads.l_status',
                'leads.tech',
                'leads.resit',
                'leads.service_type',
                'leads.created_at',
                'leads.deadline',
                'leads.emp_id',
                'leads.status',
                'leads.is_converted',
                'leads.pages',
                'leads.price',
                // Add any other leads columns you need explicitly here
                'users.name as customer_name',
                'users.email as customer_email',
                'users.countrycode as customer_country_code',
                'users.mobile_no as customer_phone'
            );

        // $query = Leads::with('user');        
   
        // Filter by order or project title
        if (!empty($this->filters['search'])) {
            $order = $this->filters['search'];
            $query->where(function ($q) use ($order) {
                $q->where('leads.order_id', 'like', '%' . $order . '%')
                    ->orWhere('leads.project_title', 'like', '%' . $order . '%');
            });
        }

        // Filter by status
        if (!empty($this->filters['status'])) {
            $query->where('leads.l_status', $this->filters['status']);
        }

        // Filter by type
        if (!empty($this->filters['type'])) {
            $type = $this->filters['type'];
            if ($type === 'Technical') {
                $query->where('leads.tech', 'on');
            } elseif ($type === 'Resit') {
                $query->where('leads.resit', 'on');
            } elseif ($type === 'First') {
                $query->where('leads.service_type', 'First Class Work');
            }
        }

        // Filter by created_at (date range)
        if (!empty($this->filters['date_from'])) {
            if (!empty($this->filters['date_to'])) {
                $query->whereBetween('leads.created_at', [$this->filters['date_from'], $this->filters['date_to']]);
            } else {
                $query->whereDate('leads.created_at', $this->filters['date_from']);
            }
        } elseif (!empty($this->filters['date_type'])) {
            // Use deadline for filtering if date_type exists
            if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
                $query->whereBetween('leads.deadline', [$this->filters['date_from'], $this->filters['date_to']]);
            } elseif (!empty($this->filters['date_from'])) {
                $query->whereDate('leads.deadline', $this->filters['date_from']);
            } else {
                $query->whereDate('leads.deadline', $this->filters['date_type']);
            }
        }

        // Filter by emp_id
        if (!empty($this->filters['uid'])) {
            $query->where('leads.emp_id', $this->filters['uid']);
        }

        // Additional fixed filters
        return $query->where('leads.status', 0)->where('leads.is_converted', 0)->orderByDesc('leads.id');

    }

    public function map($lead): array
    {
        $row = [];

        foreach ($this->columns as $column) {
            switch ($column) {
                case 'order_id':
                    $row[] = $lead->order_id;
                    break;

                case 'name':
                    $row[] = $lead->customer_name ?? '';
                    break;

                case 'email':
                    $row[] = $lead->customer_email ?? '';
                    break;

                case 'customer_country_code':
                    $row[] = $lead->customer_country_code ?? '';
                    break;

                case 'customer_phone':
                    $row[] = $lead->customer_phone ?? '';
                    break;                

                case 'order_date':
                    $row[] = $this->formatDate($lead->created_at);
                    break;

                case 'project_title':
                    $row[] = $lead->project_title ?? '';
                    break;

                case 'pages':
                    $row[] = $lead->pages ?? '';
                    break;

                case 'price':
                    $row[] = $lead->price ?? '';
                    break;

                case 'deadline':
                    $row[] = $this->formatDate($lead->deadline);
                    break;

                default:
                    $row[] = ''; // fallback for unsupported column
            }
        }

        return $row;
    }

    public function headings(): array
    {
        $labels = [
            'order_id' => 'Order ID',
            'name' => 'Name',
            'email' => 'Email',
            'customer_country_code' => 'Country Code',
            'customer_phone' => 'Phone',
            'order_date' => 'Order Date',
            'project_title' => 'Project Title',
            'pages' => 'Words',
            'price' => 'Price',
            'deadline' => 'Delivery Date',
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
