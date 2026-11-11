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
        'order_id', 'name', 'email', 'customer_country_code', 'customer_phone', 'order_date',
        'project_title', 'pages', 'price', 'deadline', 'delivery_time',
        'l_status', 'lead_status', 'source', 'assign_type', 'service_type', 'module_code',
    ];

    public function __construct($filters = [])
    {
        $this->filters = is_array($filters) ? $filters : [];
        $requestedColumns = $this->filters['selected_columns'] ?? [];
        if (is_string($requestedColumns)) {
            $requestedColumns = array_filter(array_map('trim', explode(',', $requestedColumns)));
        }

        $allowed = array_keys($this->columnLabels());
        $selected = is_array($requestedColumns)
            ? array_values(array_unique(array_intersect($requestedColumns, $allowed)))
            : [];

        $this->columns = !empty($selected) ? $selected : $this->defaultColumns;
    }

    protected function columnLabels(): array
    {
        return [
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
            'delivery_time' => 'Delivery Time',
            'l_status' => 'Status',
            'lead_status' => 'Lead Priority',
            'source' => 'Source',
            'assign_type' => 'Assign Type',
            'service_type' => 'Service Type',
            'module_code' => 'Module Code',
        ];
    }

    public function query()
    {
        $query = DB::table('leads')
            ->leftJoin('users', 'leads.emp_id', '=', 'users.id')
            ->leftJoin('sources', 'leads.lead_source', '=', 'sources.id')
            ->select(
                'leads.id',
                'leads.order_id',
                'leads.user_name',
                'leads.email as lead_email',
                'leads.countrycode as lead_country_code',
                'leads.mobile as lead_phone',
                'leads.project_title',
                'leads.l_status',
                'leads.lead_status',
                'leads.tech',
                'leads.resit',
                'leads.service_type',
                'leads.module_code',
                'leads.assign_type',
                'leads.created_at',
                'leads.create_at',
                'leads.deadline',
                'leads.delivery_time',
                'leads.emp_id',
                'leads.status',
                'leads.is_converted',
                'leads.pages',
                'leads.price',
                'users.name as customer_name',
                'users.email as customer_email',
                'users.countrycode as customer_country_code',
                'users.mobile_no as customer_phone',
                'sources.source_name as source_name'
            )
            ->where('leads.duplicate_lead', 0);

        $selectedUid = $this->filters['selectedValue'] ?? $this->filters['uid'] ?? null;
        $hasSelectedUser = false;

        if (!empty($selectedUid) && is_numeric($selectedUid) && (int) $selectedUid > 0) {
            $query->where('leads.emp_id', (int) $selectedUid);
            $hasSelectedUser = true;
        }

        $searchOrder = trim((string) ($this->filters['order'] ?? $this->filters['search_order'] ?? ''));
        $searchUser = trim((string) ($this->filters['user'] ?? $this->filters['searchInput'] ?? ''));
        $generalSearch = trim((string) ($this->filters['search'] ?? ''));

        $searchTermsToApply = [];
        if ($generalSearch !== '' && (!$hasSelectedUser || $generalSearch !== $searchUser)) {
            $searchTermsToApply[] = $generalSearch;
        }
        if ($searchOrder !== '') {
            $searchTermsToApply[] = $searchOrder;
        }
        if (!$hasSelectedUser && $searchUser !== '') {
            $searchTermsToApply[] = $searchUser;
        }

        foreach (array_unique(array_filter($searchTermsToApply)) as $searchTerm) {
            $searchUserIds = find_user_ids_by_search_term($searchTerm);
            $cleanSearchMasked = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $searchTerm));
            $cleanSearchDigits = preg_replace('/\D+/', '', $searchTerm);
            $last10 = strlen($cleanSearchDigits) >= 10 ? substr($cleanSearchDigits, -10) : $cleanSearchDigits;

            $query->where(function ($q) use ($searchTerm, $searchUserIds, $cleanSearchMasked, $cleanSearchDigits, $last10) {
                $q->where('leads.order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('leads.project_title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('leads.user_name', 'like', '%' . $searchTerm . '%');

                if (!empty($searchUserIds)) {
                    $q->orWhereIn('leads.emp_id', $searchUserIds);
                }
                if (is_numeric($searchTerm)) {
                    $q->orWhere('leads.emp_id', (int) $searchTerm);
                }

                $isEmailSearch = strpos($searchTerm, '@') !== false;

                if (!$isEmailSearch && strpos($searchTerm, '*') !== false && !empty($cleanSearchMasked) && preg_match('/\d/', $cleanSearchMasked)) {
                    $q->orWhere('leads.mobile', 'like', '%' . $cleanSearchMasked . '%')
                        ->orWhere('leads.mobile2', 'like', '%' . $cleanSearchMasked . '%')
                        ->orWhereRaw("CONCAT(IFNULL(leads.countrycode, ''), IFNULL(leads.mobile, '')) LIKE ?", ['%' . $cleanSearchMasked . '%'])
                        ->orWhere('users.mobile_no', 'like', '%' . $cleanSearchMasked . '%')
                        ->orWhere('users.mobile_no2', 'like', '%' . $cleanSearchMasked . '%')
                        ->orWhereRaw("CONCAT(IFNULL(users.countrycode, ''), IFNULL(users.mobile_no, '')) LIKE ?", ['%' . $cleanSearchMasked . '%']);
                }

                if (!$isEmailSearch && !empty($cleanSearchDigits) && strlen($cleanSearchDigits) >= 2) {
                    $q->orWhere('leads.mobile', 'like', '%' . $cleanSearchDigits . '%')
                        ->orWhere('leads.mobile', 'like', '%' . $last10 . '%')
                        ->orWhere('leads.mobile2', 'like', '%' . $cleanSearchDigits . '%')
                        ->orWhere('leads.mobile2', 'like', '%' . $last10 . '%')
                        ->orWhereRaw("CONCAT(IFNULL(leads.countrycode, ''), IFNULL(leads.mobile, '')) LIKE ?", ['%' . $cleanSearchDigits . '%'])
                        ->orWhere('users.mobile_no', 'like', '%' . $cleanSearchDigits . '%')
                        ->orWhere('users.mobile_no', 'like', '%' . $last10 . '%')
                        ->orWhere('users.mobile_no2', 'like', '%' . $cleanSearchDigits . '%')
                        ->orWhere('users.mobile_no2', 'like', '%' . $last10 . '%')
                        ->orWhereRaw("CONCAT(IFNULL(users.countrycode, ''), IFNULL(users.mobile_no, '')) LIKE ?", ['%' . $cleanSearchDigits . '%']);
                }

                if ($isEmailSearch) {
                    $cleanEmail = preg_replace('/\*+/', '%', $searchTerm);
                    $q->orWhere('leads.email', 'like', '%' . $cleanEmail . '%')
                        ->orWhere('users.email', 'like', '%' . $cleanEmail . '%');
                } else {
                    $q->orWhere('leads.email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('users.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('users.email', 'like', '%' . $searchTerm . '%');
                }
            });
        }

        $leadStatusTab = trim((string) ($this->filters['lead_status_tab'] ?? ''));
        if ($leadStatusTab !== '' && $leadStatusTab !== 'All') {
            if (in_array($leadStatusTab, ['Hot', 'Warm', 'Cold'], true)) {
                $query->where('leads.lead_status', $leadStatusTab);
            } else {
                $query->where('leads.l_status', $leadStatusTab);
            }
        }

        $status = trim((string) ($this->filters['status'] ?? ''));
        if ($status !== '' && $status !== 'All') {
            if (in_array($status, ['Hot', 'Warm', 'Cold'], true)) {
                $query->where('leads.lead_status', $status);
            } else {
                $query->where('leads.l_status', $status);
            }
        }

        $type = trim((string) ($this->filters['type'] ?? ''));
        if ($type !== '') {
            if ($type === 'Technical') {
                $query->where('leads.tech', 'on');
            } elseif ($type === 'Resit') {
                $query->where('leads.resit', 'on');
            } elseif ($type === 'First') {
                $query->where('leads.service_type', 'First Class Work');
            }
        }

        $dateFrom = trim((string) ($this->filters['date_from'] ?? ''));
        $dateTo = trim((string) ($this->filters['date_to'] ?? ''));
        $dateType = trim((string) ($this->filters['date_type'] ?? ''));

        if ($dateType === 'deadline') {
            if ($dateFrom !== '' && $dateTo !== '') {
                $query->whereDate('leads.deadline', '>=', $dateFrom)
                    ->whereDate('leads.deadline', '<=', $dateTo);
            } elseif ($dateFrom !== '') {
                $query->whereDate('leads.deadline', '=', $dateFrom);
            } elseif ($dateTo !== '') {
                $query->whereDate('leads.deadline', '=', $dateTo);
            }
        } else {
            $createdExpr = "COALESCE(NULLIF(leads.created_at, ''), leads.create_at)";
            if ($dateFrom !== '' && $dateTo !== '') {
                $query->whereRaw("DATE($createdExpr) >= ?", [$dateFrom])
                    ->whereRaw("DATE($createdExpr) <= ?", [$dateTo]);
            } elseif ($dateFrom !== '') {
                $query->whereRaw("DATE($createdExpr) = ?", [$dateFrom]);
            } elseif ($dateTo !== '') {
                $query->whereRaw("DATE($createdExpr) = ?", [$dateTo]);
            }
        }

        $groupId = $this->filters['group_id'] ?? null;
        if ($groupId !== null && $groupId !== '') {
            $query->whereExists(function ($sub) use ($groupId) {
                $sub->select(DB::raw(1))
                    ->from('group_master_user')
                    ->whereColumn('group_master_user.user_id', 'leads.emp_id')
                    ->where('group_master_user.group_master_id', $groupId);
            });
        }

        $assignType = $this->filters['assign_type'] ?? null;
        if ($assignType !== null && $assignType !== '') {
            $query->where('leads.assign_type', (int) $assignType);
        }

        $leadSource = $this->filters['lead_source'] ?? null;
        if ($leadSource !== null && $leadSource !== '') {
            $cleanSource = is_string($leadSource) && str_starts_with($leadSource, 'source_')
                ? substr($leadSource, 7)
                : $leadSource;
            $query->where('leads.lead_source', $cleanSource);
        }

        return $query->where('leads.status', 0)
            ->where('leads.is_converted', 0)
            ->orderByDesc('leads.id');
    }

    public function map($lead): array
    {
        $name = trim((string) ($lead->customer_name ?: $lead->user_name ?: ''));
        $email = trim((string) ($lead->customer_email ?: $lead->lead_email ?: ''));
        $countryCode = trim((string) ($lead->customer_country_code ?: $lead->lead_country_code ?: ''));
        if ($countryCode !== '') {
            $countryCode = str_starts_with($countryCode, '+')
                ? $countryCode
                : '+' . ltrim($countryCode, '+');
        }
        $phone = trim((string) ($lead->customer_phone ?: $lead->lead_phone ?: ''));
        $orderDateRaw = !empty($lead->create_at) ? $lead->create_at : $lead->created_at;

        $row = [];

        foreach ($this->columns as $column) {
            switch ($column) {
                case 'order_id':
                    $row[] = $lead->order_id ?? '';
                    break;

                case 'name':
                    $row[] = $name;
                    break;

                case 'email':
                    $row[] = $email;
                    break;

                case 'customer_country_code':
                    $row[] = $countryCode;
                    break;

                case 'customer_phone':
                    $row[] = $phone;
                    break;

                case 'order_date':
                    $row[] = $this->formatDate($orderDateRaw);
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

                case 'delivery_time':
                    $row[] = $lead->delivery_time ?? '';
                    break;

                case 'l_status':
                    $row[] = $lead->l_status ?? '';
                    break;

                case 'lead_status':
                    $row[] = $lead->lead_status ?? '';
                    break;

                case 'source':
                    $row[] = $lead->source_name ?? '';
                    break;

                case 'assign_type':
                    $row[] = ((int) ($lead->assign_type ?? 0) === 1) ? "Let's Lern" : 'AIN';
                    break;

                case 'service_type':
                    $row[] = $lead->service_type ?? '';
                    break;

                case 'module_code':
                    $row[] = $lead->module_code ?? '';
                    break;

                default:
                    $row[] = '';
            }
        }

        return $row;
    }

    public function headings(): array
    {
        $labels = $this->columnLabels();

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
