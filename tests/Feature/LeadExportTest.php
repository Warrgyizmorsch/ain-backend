<?php

namespace Tests\Feature;

use App\Exports\LeadsExport;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LeadExportTest extends TestCase
{
    protected string $prefix = 'ZZTEST_LEADEXP_';

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupSyntheticFixtures();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        $this->cleanupSyntheticFixtures();
        parent::tearDown();
    }

    private function cleanupSyntheticFixtures(): void
    {
        $userIds = DB::table('users')->where('name', 'like', $this->prefix . '%')->pluck('id')->all();
        if (!empty($userIds)) {
            DB::table('group_master_user')->whereIn('user_id', $userIds)->delete();
            Cache::forget('lead_export_' . $userIds[0]);
            foreach ($userIds as $uid) {
                Cache::forget('lead_export_' . $uid);
                Cache::forget('lead_export_pending_' . $uid);
                Cache::forget('lead_export_failed_' . $uid);
            }
        }

        $groupIds = DB::table('group_masters')->where('name', 'like', $this->prefix . '%')->pluck('id')->all();
        if (!empty($groupIds)) {
            DB::table('group_master_user')->whereIn('group_master_id', $groupIds)->delete();
            DB::table('group_masters')->whereIn('id', $groupIds)->delete();
        }

        DB::table('leads')->where('order_id', 'like', $this->prefix . '%')->delete();
        DB::table('sources')->where('source_name', 'like', $this->prefix . '%')->delete();
        DB::table('users')->where('name', 'like', $this->prefix . '%')->delete();

        $testStorageFile = 'exports/' . $this->prefix . 'test.xlsx';
        if (Storage::disk('public')->exists($testStorageFile)) {
            Storage::disk('public')->delete($testStorageFile);
        }
    }

    private function createUser(int $roleId = 2, array $overrides = []): User
    {
        static $seq = 1;
        $n = $seq++;
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => $this->prefix . 'User_' . $n,
            'email' => strtolower($this->prefix) . 'u' . $n . '@example.test',
            'countrycode' => '+44',
            'mobile_no' => '790055' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'role_id' => $roleId,
            'flag' => 0,
            'password' => Hash::make('secret123'),
            'created_at' => '2026-01-15 10:00:00',
            'updated_at' => '2026-01-15 10:00:00',
        ], $overrides));

        Cache::forget('role-allowed-routes-' . $roleId);

        return User::findOrFail($id);
    }

    private function createLead(array $overrides = []): int
    {
        static $seq = 1;
        $n = $seq++;

        return DB::table('leads')->insertGetId(array_merge([
            'order_id' => $this->prefix . 'ORD_' . $n,
            'emp_id' => null,
            'user_name' => $this->prefix . 'LeadName_' . $n,
            'email' => strtolower($this->prefix) . 'l' . $n . '@example.test',
            'countrycode' => '+44',
            'mobile' => '791155' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'project_title' => $this->prefix . 'Title_' . $n,
            'pages' => '2500',
            'price' => 150.00,
            'deadline' => '2026-12-25',
            'delivery_time' => '18:00',
            'l_status' => 'Quote',
            'lead_status' => 'Warm',
            'assign_type' => '0',
            'status' => 0,
            'is_converted' => 0,
            'duplicate_lead' => 0,
            'flag' => 0,
            'frontendorder' => 0,
            'is_app_lead' => 0,
            'create_at' => '2026-11-10 10:00:00',
            'created_at' => '2026-11-10 10:00:00',
        ], $overrides));
    }

    /**
     * Parse CSV response content into [headings, rows].
     */
    private function parseCsvContent(string $raw): array
    {
        $clean = ltrim($raw, "\xEF\xBB\xBF");
        $lines = preg_split("/\r\n|\n|\r/", trim($clean));
        $parsed = array_map(fn ($line) => str_getcsv($line), array_filter($lines, fn ($l) => $l !== ''));

        $headings = array_shift($parsed) ?? [];

        return [$headings, $parsed];
    }

    public function test_lead_page_renders_visible_export_button_for_admins_only(): void
    {
        $superAdmin = $this->createUser(1);
        $subAdmin = $this->createUser(9);
        $seo = $this->createUser(10); // SEO has menu 11 ("lead") but must NOT see Export

        foreach ([$superAdmin, $subAdmin] as $authorized) {
            $resp = $this->actingAs($authorized)->get('/lead');
            $resp->assertStatus(200);
            $html = $resp->getContent();

            $this->assertMatchesRegularExpression(
                '/<button[^>]*id="export-btn"[^>]*>/i',
                $html,
                'Export button should render for authorized role ' . $authorized->role_id
            );
            $this->assertDoesNotMatchRegularExpression(
                '/<button[^>]*style="[^"]*display\s*:\s*none[^"]*"[^>]*id="export-btn"/i',
                $html,
                'Export button must be visible immediately without display:none'
            );
            $this->assertStringContainsString('Choose Export Option', $html);
            $this->assertStringContainsString('Export All', $html);
            $this->assertStringContainsString('Custom Export', $html);
            $this->assertStringNotContainsString('localStorage.setItem("leadExportStatus"', $html);
        }

        $seoResp = $this->actingAs($seo)->get('/lead');
        $seoResp->assertStatus(200);
        $this->assertDoesNotMatchRegularExpression('/id="export-btn"/i', $seoResp->getContent());

        // Legacy /leads export button visibility
        $legacySubResp = $this->actingAs($subAdmin)->get('/leads');
        $legacySubResp->assertStatus(200);
        $this->assertStringContainsString('onclick="leadsExport()"', $legacySubResp->getContent());

        $legacySeoResp = $this->actingAs($seo)->get('/leads');
        $legacySeoResp->assertStatus(200);
        $this->assertStringNotContainsString('onclick="leadsExport()"', $legacySeoResp->getContent());
    }

    public function test_lead_export_endpoints_require_admin_or_sub_admin(): void
    {
        $superAdmin = $this->createUser(1);
        $subAdmin = $this->createUser(9);
        $customer = $this->createUser(2);
        $marketing = $this->createUser(4);
        $seo = $this->createUser(10);

        // Unauthenticated requests
        $this->post('/lead/export')->assertRedirect('/login');
        $this->get('/lead/export')->assertRedirect('/login');
        $this->get('/lead/export/status')->assertRedirect('/login');
        $this->get('/lead/export/download')->assertRedirect('/login');
        $this->get('/export-leads')->assertRedirect('/login');

        // Non-admin roles must receive 403
        foreach ([$customer, $marketing, $seo] as $nonAdmin) {
            $this->actingAs($nonAdmin)->post('/lead/export')->assertStatus(403);
            $this->actingAs($nonAdmin)->get('/lead/export')->assertStatus(403);
            $this->actingAs($nonAdmin)->get('/lead/export/status')->assertStatus(403);
            $this->actingAs($nonAdmin)->get('/lead/export/download')->assertStatus(403);
            $this->actingAs($nonAdmin)->get('/export-leads')->assertStatus(403);
        }

        // Super Admin and Sub Admin succeed
        foreach ([$superAdmin, $subAdmin] as $admin) {
            $postCsv = $this->actingAs($admin)->post('/lead/export', ['order' => $this->prefix]);
            $postCsv->assertStatus(200);
            $this->assertStringContainsString('text/csv', (string) $postCsv->headers->get('Content-Type'));
            $postCsv->streamedContent();

            $getCsv = $this->actingAs($admin)->get('/lead/export?order=' . $this->prefix);
            $getCsv->assertStatus(200);
            $getCsv->streamedContent();

            $this->actingAs($admin)->getJson('/lead/export/status')
                ->assertStatus(200)
                ->assertJson(['status' => 'idle', 'progress' => 0]);

            $this->actingAs($admin)->get('/export-leads?additional_filter1=' . $this->prefix)
                ->assertStatus(200)
                ->streamedContent();
        }
    }

    public function test_lead_export_filters_match_ui_and_handle_fallbacks(): void
    {
        $admin = $this->createUser(1);

        $groupId = DB::table('group_masters')->insertGetId([
            'name' => $this->prefix . 'VIPGroup',
            'status' => 1,
            'created_at' => '2026-11-01 00:00:00',
            'updated_at' => '2026-11-01 00:00:00',
        ]);

        $sourceId = DB::table('sources')->insertGetId([
            'source_name' => $this->prefix . 'WhatsAppSrc',
            'is_delete' => 0,
        ]);

        $clientA = $this->createUser(2, [
            'name' => $this->prefix . 'ClientA',
            'email' => 'alpha_' . strtolower($this->prefix) . '1@example.test',
            'countrycode' => '44',
            'mobile_no' => '7900112233',
        ]);
        DB::table('group_master_user')->insert([
            'group_master_id' => $groupId,
            'user_id' => $clientA->id,
        ]);

        // Active lead A: NULL created_at, populated create_at, CSV special chars, LetsLern, Hot/Price, Tech+Resit+First
        $this->createLead([
            'order_id' => $this->prefix . 'ACT_A',
            'emp_id' => $clientA->id,
            'user_name' => 'Fallback Should Not Override User A',
            'project_title' => 'Project "Alpha", Phase 2',
            'pages' => '3000',
            'price' => 250.50,
            'deadline' => '2026-12-20',
            'delivery_time' => '14:30',
            'l_status' => 'Price',
            'lead_status' => 'Hot',
            'tech' => 'on',
            'resit' => 'on',
            'service_type' => 'First Class Work',
            'module_code' => 'MOD-991',
            'assign_type' => '1',
            'lead_source' => $sourceId,
            'create_at' => '2026-11-05 14:20:00',
            'created_at' => null,
        ]);

        // Active lead B: no linked user (emp_id null), falls back to leads.user_name/email/countrycode/mobile
        $this->createLead([
            'order_id' => $this->prefix . 'ACT_B',
            'emp_id' => null,
            'user_name' => $this->prefix . 'LeadOnlyB',
            'email' => 'zzleadb2@example.test',
            'countrycode' => '+91',
            'mobile' => '9876501234',
            'project_title' => 'Plain Essay Title B',
            'pages' => '1500',
            'price' => 95.00,
            'deadline' => '2026-12-28',
            'l_status' => 'Quote',
            'lead_status' => 'Warm',
            'assign_type' => '0',
            'create_at' => '2026-11-06 09:00:00',
            'created_at' => '2026-11-06 09:00:00',
        ]);

        // Excluded leads
        $this->createLead(['order_id' => $this->prefix . 'CANC_C', 'status' => 1]);
        $this->createLead(['order_id' => $this->prefix . 'CONV_D', 'is_converted' => 1]);
        $this->createLead(['order_id' => $this->prefix . 'DUPL_E', 'duplicate_lead' => 1]);

        $exportIdsFor = function (array $filters) use ($admin): array {
            $exportResp = $this->actingAs($admin)->post('/lead/export', $filters);
            $exportResp->assertStatus(200);
            [$headings, $rows] = $this->parseCsvContent($exportResp->streamedContent());
            $idx = array_search('Order ID', $headings, true);
            $this->assertNotFalse($idx);

            $ids = array_values(array_map(fn ($r) => $r[$idx] ?? '', $rows));
            sort($ids);

            return $ids;
        };

        $filterIdsFor = function (array $filters) use ($admin): array {
            $filterResp = $this->actingAs($admin)->postJson('/lead/filter', array_merge($filters, ['limit' => 50]));
            $filterResp->assertStatus(200);
            $html = (string) ($filterResp->json('html') ?? '');

            $matched = [];
            foreach (['ACT_A', 'ACT_B', 'CANC_C', 'CONV_D', 'DUPL_E'] as $suffix) {
                $code = $this->prefix . $suffix;
                if (str_contains($html, $code)) {
                    $matched[] = $code;
                }
            }
            sort($matched);

            return $matched;
        };

        // 1. Only active non-converted non-duplicate leads exported, matching /lead/filter
        $expectedBoth = [$this->prefix . 'ACT_A', $this->prefix . 'ACT_B'];
        $baseFilter = ['order' => $this->prefix];
        $this->assertSame($expectedBoth, $exportIdsFor($baseFilter));
        $this->assertSame($expectedBoth, $filterIdsFor($baseFilter));

        // 2. Filter parity checks across all filter dimensions
        $cases = [
            ['selectedValue' => $clientA->id, 'order' => $this->prefix],
            ['lead_status_tab' => 'Hot', 'order' => $this->prefix],
            ['status' => 'Price', 'order' => $this->prefix],
            ['type' => 'Technical', 'order' => $this->prefix],
            ['type' => 'Resit', 'order' => $this->prefix],
            ['type' => 'First', 'order' => $this->prefix],
            ['date_from' => '2026-11-05', 'date_to' => '2026-11-05', 'order' => $this->prefix],
            ['date_from' => '2026-11-05', 'date_to' => '2026-11-05', 'date_type' => 'created_at', 'order' => $this->prefix],
            ['date_from' => '2026-12-20', 'date_type' => 'deadline', 'order' => $this->prefix],
            ['assign_type' => '1', 'order' => $this->prefix],
            ['lead_source' => $sourceId, 'order' => $this->prefix],
            ['lead_source' => 'source_' . $sourceId, 'order' => $this->prefix],
            ['group_id' => $groupId, 'order' => $this->prefix],
        ];

        foreach ($cases as $filters) {
            $this->assertSame([$this->prefix . 'ACT_A'], $exportIdsFor($filters));
            $this->assertSame([$this->prefix . 'ACT_A'], $filterIdsFor($filters));
        }

        // Case B checks (assign_type = '0', masked email search, phone digits search, Quote tab)
        $casesB = [
            ['assign_type' => '0', 'order' => $this->prefix],
            ['lead_status_tab' => 'Quote', 'order' => $this->prefix],
            ['status' => 'Warm', 'order' => $this->prefix],
            ['user' => '9876501234', 'order' => $this->prefix],
            ['user' => 'z******2@example.test', 'order' => $this->prefix],
        ];

        foreach ($casesB as $filters) {
            $this->assertSame([$this->prefix . 'ACT_B'], $exportIdsFor($filters));
            $this->assertSame([$this->prefix . 'ACT_B'], $filterIdsFor($filters));
        }

        // 'All' status tab should not restrict A or B
        $this->assertSame($expectedBoth, $exportIdsFor(['lead_status_tab' => 'All', 'order' => $this->prefix]));

        // 3. Verify CSV column values, contact fallbacks, country code + prefix, date fallback, and quotes/commas escaping
        $fullResp = $this->actingAs($admin)->post('/lead/export', ['order' => $this->prefix]);
        $fullResp->assertStatus(200);
        [$headings, $rows] = $this->parseCsvContent($fullResp->streamedContent());
        $this->assertCount(2, $rows);

        $rowById = [];
        $orderIdCol = array_search('Order ID', $headings, true);
        foreach ($rows as $r) {
            $rowById[$r[$orderIdCol]] = array_combine($headings, $r);
        }

        $rowA = $rowById[$this->prefix . 'ACT_A'];
        $this->assertSame($clientA->name, $rowA['Name']);
        $this->assertSame($clientA->email, $rowA['Email']);
        $this->assertSame('+44', $rowA['Country Code']);
        $this->assertSame('7900112233', $rowA['Phone']);
        $this->assertSame('05 Nov 2026', $rowA['Order Date']);
        $this->assertSame('Project "Alpha", Phase 2', $rowA['Project Title']);
        $this->assertSame('20 Dec 2026', $rowA['Delivery Date']);
        $this->assertSame('14:30', $rowA['Delivery Time']);
        $this->assertSame('Price', $rowA['Status']);
        $this->assertSame('Hot', $rowA['Lead Priority']);
        $this->assertSame($this->prefix . 'WhatsAppSrc', $rowA['Source']);
        $this->assertSame("Let's Lern", $rowA['Assign Type']);
        $this->assertSame('First Class Work', $rowA['Service Type']);
        $this->assertSame('MOD-991', $rowA['Module Code']);

        $rowB = $rowById[$this->prefix . 'ACT_B'];
        $this->assertSame($this->prefix . 'LeadOnlyB', $rowB['Name']);
        $this->assertSame('zzleadb2@example.test', $rowB['Email']);
        $this->assertSame('+91', $rowB['Country Code']);
        $this->assertSame('9876501234', $rowB['Phone']);
        $this->assertSame('06 Nov 2026', $rowB['Order Date']);
        $this->assertSame('AIN', $rowB['Assign Type']);

        // 4. Custom selected columns via POST and comma-separated GET
        $customResp = $this->actingAs($admin)->post('/lead/export', [
            'order' => $this->prefix . 'ACT_A',
            'selected_columns' => ['order_id', 'name', 'source', 'assign_type'],
        ]);
        $customResp->assertStatus(200);
        [$cHead, $cRows] = $this->parseCsvContent($customResp->streamedContent());
        $this->assertSame(['Order ID', 'Name', 'Source', 'Assign Type'], $cHead);
        $this->assertSame([[$this->prefix . 'ACT_A', $clientA->name, $this->prefix . 'WhatsAppSrc', "Let's Lern"]], $cRows);

        $getCustomResp = $this->actingAs($admin)->get('/lead/export?order=' . $this->prefix . 'ACT_B&selected_columns=order_id,assign_type');
        $getCustomResp->assertStatus(200);
        [$gHead, $gRows] = $this->parseCsvContent($getCustomResp->streamedContent());
        $this->assertSame(['Order ID', 'Assign Type'], $gHead);
        $this->assertSame([[$this->prefix . 'ACT_B', 'AIN']], $gRows);
    }

    public function test_leads_export_class_status_download_and_legacy_export_leads_work(): void
    {
        $subAdmin = $this->createUser(9);

        $activeId = $this->createLead([
            'order_id' => $this->prefix . 'LEG_1',
            'emp_id' => null,
            'user_name' => $this->prefix . 'LegacyName',
            'email' => 'legacy_' . strtolower($this->prefix) . '@example.test',
            'mobile' => '7922110099',
            'project_title' => 'Legacy, "Quoted" Title',
            'l_status' => 'Hot',
            'lead_status' => 'Hot',
            'assign_type' => '0',
            'create_at' => '2026-11-08 11:00:00',
            'created_at' => null,
        ]);
        $this->createLead(['order_id' => $this->prefix . 'LEG_CONV', 'is_converted' => 1]);
        $this->createLead(['order_id' => $this->prefix . 'LEG_DUPL', 'duplicate_lead' => 1]);

        // LeadsExport query + map verification
        $exporter = new LeadsExport([
            'order' => $this->prefix . 'LEG_',
            'assign_type' => '0',
            'selected_columns' => ['order_id', 'name', 'email', 'order_date', 'assign_type'],
        ]);
        $rows = $exporter->query()->get();
        $this->assertCount(1, $rows);
        $this->assertSame($activeId, (int) $rows->first()->id);
        $this->assertSame(['Order ID', 'Name', 'Email', 'Order Date', 'Assign Type'], $exporter->headings());
        $mapped = $exporter->map($rows->first());
        $this->assertSame([
            $this->prefix . 'LEG_1',
            $this->prefix . 'LegacyName',
            'legacy_' . strtolower($this->prefix) . '@example.test',
            '08 Nov 2026',
            'AIN',
        ], $mapped);

        // Export status + download lifecycle
        Cache::put('lead_export_pending_' . $subAdmin->id, true, now()->addMinutes(5));
        $this->actingAs($subAdmin)->getJson('/lead/export/status')
            ->assertStatus(200)
            ->assertJson(['status' => 'pending']);

        Cache::forget('lead_export_pending_' . $subAdmin->id);
        Cache::put('lead_export_failed_' . $subAdmin->id, 'Test failure reason', now()->addMinutes(5));
        $this->actingAs($subAdmin)->getJson('/lead/export/status')
            ->assertStatus(200)
            ->assertJson(['status' => 'failed', 'message' => 'Test failure reason']);

        $testRelPath = 'exports/' . $this->prefix . 'test.xlsx';
        Storage::disk('public')->put($testRelPath, 'dummy-export-bytes');
        Cache::put('lead_export_' . $subAdmin->id, $testRelPath, now()->addMinutes(5));

        $statusReady = $this->actingAs($subAdmin)->getJson('/lead/export/status');
        $statusReady->assertStatus(200)
            ->assertJson([
                'status' => 'ready',
                'progress' => 100,
                'url' => route('lead.export.download'),
            ]);

        $dlResp = $this->actingAs($subAdmin)->get('/lead/export/download');
        $dlResp->assertStatus(200);

        // Legacy /export-leads endpoint excludes converted & duplicate leads and uses fallbacks
        $legacyResp = $this->actingAs($subAdmin)->get('/export-leads?additional_filter1=' . $this->prefix . 'LEG_');
        $legacyResp->assertStatus(200);
        [$lHead, $lRows] = $this->parseCsvContent($legacyResp->streamedContent());
        $this->assertSame('Order Code', $lHead[0]);
        $this->assertCount(1, $lRows);
        $this->assertSame($this->prefix . 'LEG_1', $lRows[0][0]);
        $this->assertSame($this->prefix . 'LegacyName', $lRows[0][1]);
        $this->assertSame('legacy_' . strtolower($this->prefix) . '@example.test', $lRows[0][2]);
        $this->assertSame('7922110099', $lRows[0][3]);
        $this->assertSame('Legacy, "Quoted" Title', $lRows[0][6]);
    }
}

