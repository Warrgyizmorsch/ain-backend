<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-duplicates {--type=all : Check users, leads, orders, or all} {--limit=10 : Max sample rows to display}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze and display duplicate entries in users, leads, and orders by email, phone, name, and order_id.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = strtolower((string) $this->option('type'));
        $limit = (int) $this->option('limit') ?: 10;

        $this->info("==================================================");
        $this->info("           CRM DUPLICATE ANALYSIS REPORT          ");
        $this->info("==================================================");

        if (in_array($type, ['all', 'users'])) {
            $this->checkUsers($limit);
        }

        if (in_array($type, ['all', 'leads'])) {
            $this->checkLeads($limit);
        }

        if (in_array($type, ['all', 'orders'])) {
            $this->checkOrders($limit);
        }

        $this->info("\nAnalysis complete!");
        return 0;
    }

    protected function checkUsers(int $limit): void
    {
        $this->info("\n--- [1] USERS TABLE DUPLICATES ---");

        // Duplicate Emails
        $dupEmails = DB::table('users')
            ->select('email', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(id) as user_ids'))
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->groupBy('email')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Duplicate Emails in Users: <comment>{$dupEmails->count()}</comment> unique emails repeated across multiple accounts.");
        if ($dupEmails->isNotEmpty()) {
            $tableRows = $dupEmails->take($limit)->map(function ($r) {
                return [
                    'Email' => $r->email,
                    'Repeats' => $r->count,
                    'User IDs' => $r->user_ids,
                ];
            });
            $this->table(['Email', 'Repeats', 'User IDs'], $tableRows);
        }

        // Duplicate Mobiles
        $dupMobiles = DB::table('users')
            ->select('mobile_no', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(id) as user_ids'))
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->groupBy('mobile_no')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Duplicate Mobiles in Users: <comment>{$dupMobiles->count()}</comment> numbers repeated across multiple accounts.");
        if ($dupMobiles->isNotEmpty()) {
            $tableRows = $dupMobiles->take($limit)->map(function ($r) {
                return [
                    'Mobile No' => $r->mobile_no,
                    'Repeats' => $r->count,
                    'User IDs' => strlen($r->user_ids) > 40 ? substr($r->user_ids, 0, 40) . '...' : $r->user_ids,
                ];
            });
            $this->table(['Mobile No', 'Repeats', 'User IDs'], $tableRows);
        }
    }

    protected function checkLeads(int $limit): void
    {
        $this->info("\n--- [2] LEADS TABLE DUPLICATES ---");

        $totalLeads = DB::table('leads')->count();
        $this->line("Total Leads in Database: <comment>{$totalLeads}</comment>");

        // 1. Same Mobile + Same Project Title (Potential Double Clicks / Duplicate Leads)
        $sameMobileAndTitle = DB::table('leads')
            ->select('mobile', 'project_title', DB::raw('DATE(create_at) as created_date'), DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(id) as lead_ids'))
            ->whereNotNull('mobile')
            ->where('mobile', '!=', '')
            ->groupBy('mobile', 'project_title', 'created_date')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Exact Duplicate Leads (Same Mobile + Same Title + Same Date): <comment>{$sameMobileAndTitle->count()}</comment> groups.");
        if ($sameMobileAndTitle->isNotEmpty()) {
            $tableRows = $sameMobileAndTitle->take($limit)->map(function ($r) {
                return [
                    'Mobile' => $r->mobile,
                    'Project Title' => strlen((string) $r->project_title) > 30 ? substr((string) $r->project_title, 0, 30) . '...' : ($r->project_title ?: '[Blank]'),
                    'Date' => $r->created_date,
                    'Count' => $r->count,
                    'Lead IDs' => strlen($r->lead_ids) > 30 ? substr($r->lead_ids, 0, 30) . '...' : $r->lead_ids,
                ];
            });
            $this->table(['Mobile', 'Project Title', 'Date', 'Count', 'Lead IDs'], $tableRows);
        }

        // 2. Duplicate order_id in leads
        $dupOrderIds = DB::table('leads')
            ->select('order_id', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(id) as lead_ids'))
            ->whereNotNull('order_id')
            ->where('order_id', '!=', '')
            ->groupBy('order_id')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Leads sharing the exact same Order ID (UKS code): <comment>{$dupOrderIds->count()}</comment> order codes repeated.");
        if ($dupOrderIds->isNotEmpty()) {
            $tableRows = $dupOrderIds->take($limit)->map(function ($r) {
                return [
                    'Order ID' => $r->order_id,
                    'Repeats' => $r->count,
                    'Lead IDs' => $r->lead_ids,
                ];
            });
            $this->table(['Order ID', 'Repeats', 'Lead IDs'], $tableRows);
        }

        // 3. Repeat Clients by Mobile
        $repeatClients = DB::table('leads')
            ->select('mobile', DB::raw('count(*) as count'))
            ->whereNotNull('mobile')
            ->where('mobile', '!=', '')
            ->groupBy('mobile')
            ->having('count', '>', 1)
            ->count();
        $this->line("• Repeat Clients (Mobiles with multiple leads across time): <comment>{$repeatClients}</comment> clients.");
    }

    protected function checkOrders(int $limit): void
    {
        $this->info("\n--- [3] ORDERS TABLE DUPLICATES ---");

        $totalOrders = DB::table('orders')->count();
        $this->line("Total Orders in Database: <comment>{$totalOrders}</comment>");

        // Duplicate UKS order_id in orders
        $dupOrders = DB::table('orders')
            ->select('order_id', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(id) as order_db_ids'))
            ->groupBy('order_id')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Duplicate Order Code (UKSxxxx) in Orders table: <comment>{$dupOrders->count()}</comment> codes repeated.");
        if ($dupOrders->isNotEmpty()) {
            $tableRows = $dupOrders->take($limit)->map(function ($r) {
                return [
                    'Order ID' => $r->order_id,
                    'Repeats' => $r->count,
                    'Orders Primary IDs' => $r->order_db_ids,
                ];
            });
            $this->table(['Order ID', 'Repeats', 'Orders Primary IDs'], $tableRows);
        }

        // Duplicate lead_id in orders
        $dupLeads = DB::table('orders')
            ->select('lead_id', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(order_id) as order_codes'))
            ->whereNotNull('lead_id')
            ->groupBy('lead_id')
            ->having('count', '>', 1)
            ->orderByDesc('count')
            ->get();

        $this->line("• Multiple Orders sharing the same Lead ID: <comment>{$dupLeads->count()}</comment> leads converted multiple times.");
        if ($dupLeads->isNotEmpty()) {
            $tableRows = $dupLeads->take($limit)->map(function ($r) {
                return [
                    'Lead ID' => $r->lead_id,
                    'Orders Count' => $r->count,
                    'Order Codes' => strlen($r->order_codes) > 40 ? substr($r->order_codes, 0, 40) . '...' : $r->order_codes,
                ];
            });
            $this->table(['Lead ID', 'Orders Count', 'Order Codes'], $tableRows);
        }
    }
}
