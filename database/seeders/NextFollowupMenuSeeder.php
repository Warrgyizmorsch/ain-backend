<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Team;

class NextFollowupMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Position Next Follow-ups right after Lead:
        // Lead has id 11 and sort_order 4.
        $leadMenu = DB::table('menu')->where('routes', 'lead')->first();
        $targetSortOrder = $leadMenu ? ((int)$leadMenu->sort_order + 1) : 5;

        // Find or create Next Follow-ups menu
        $menu = DB::table('menu')->where('routes', 'next-followups')->first();
        if ($menu) {
            DB::table('menu')->where('id', $menu->id)->update([
                'menu_name'  => 'Next Follow-ups',
                'icon_class' => 'fa fa-clock-o',
                'show_menu'  => 'Y',
                'routes'     => 'next-followups',
                'sort_order' => $targetSortOrder,
                'parent_id'  => null,
                'updated_at' => $now,
            ]);
            $menuId = $menu->id;
        } else {
            $menuId = DB::table('menu')->insertGetId([
                'menu_name'  => 'Next Follow-ups',
                'icon_class' => 'fa fa-clock-o',
                'show_menu'  => 'Y',
                'routes'     => 'next-followups',
                'sort_order' => $targetSortOrder,
                'parent_id'  => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Adjust other top-level menu sort orders so Next Follow-ups is strictly right after Lead:
        // Leads: 4, Next Follow-ups: 5, Cancel Leads: 6, Success Tracking: 7, Delevery Followups: 8...
        DB::table('menu')->where('routes', 'c-leads')->where('sort_order', '<=', $targetSortOrder)->update(['sort_order' => $targetSortOrder + 1]);
        DB::table('menu')->where('routes', 'follow-up')->where('sort_order', '<=', $targetSortOrder + 1)->update(['sort_order' => $targetSortOrder + 2]);
        DB::table('menu')->where('routes', 'order-feedback')->where('sort_order', '<=', $targetSortOrder + 2)->update(['sort_order' => $targetSortOrder + 3]);

        // 2. Assign permissions to Super Admin (1), Sub Admin (9), Marketing Team (4)
        $targetRoleIds = [1, 9, 4];

        foreach ($targetRoleIds as $roleId) {
            $perm = DB::table('permission')->where('role_id', $roleId)->first();
            if ($perm) {
                $menuIds = json_decode($perm->menu_id, true) ?? [];
                $menuIds = array_map('strval', $menuIds);
                if (!in_array((string)$menuId, $menuIds, true)) {
                    $menuIds[] = (string)$menuId;
                    DB::table('permission')->where('role_id', $roleId)->update([
                        'menu_id'    => json_encode(array_values(array_unique($menuIds))),
                        'updated_at' => $now,
                    ]);
                }
            } else {
                DB::table('permission')->insert([
                    'role_id'    => $roleId,
                    'menu_id'    => json_encode([(string)$menuId]),
                    'submenu_id' => json_encode([]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            Cache::forget('role-allowed-routes-' . $roleId);
            Cache::forget('role-allowed-routes-' . (int)$roleId);
        }

        // 3. Add team-Gamma to teams table if not exists
        $gammaTeam = DB::table('teams')->where('team_name', 'team-Gamma')->orWhere('team_name', 'Gamma')->first();
        if (!$gammaTeam) {
            DB::table('teams')->insert([
                'team_name'   => 'team-Gamma',
                'priority'    => 3,
                'percentage'  => 33.33,
                'created_by'  => 1,
                'is_delete'   => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        } else {
            DB::table('teams')->where('id', $gammaTeam->id)->update([
                'is_delete'   => 0,
                'updated_at'  => $now,
            ]);
        }

        // Recalculate equal percentage for all active teams (e.g. 3 teams = 33.33% each)
        $activeTeams = DB::table('teams')->where('is_delete', 0)->get();
        if ($activeTeams->count() > 0) {
            $equalPct = round(100 / $activeTeams->count(), 2);
            DB::table('teams')->where('is_delete', 0)->update(['percentage' => $equalPct]);
        }

        // 4. Add Gamma to group_masters table if not exists (for User Group dropdowns in leads & orders)
        if (\Illuminate\Support\Facades\Schema::hasTable('group_masters')) {
            $gammaGroup = DB::table('group_masters')->where('name', 'Gamma')->orWhere('name', 'team-Gamma')->first();
            if (!$gammaGroup) {
                DB::table('group_masters')->insert([
                    'name'        => 'Gamma',
                    'status'      => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // Clear all relevant caches
        Cache::flush();
        Cache::forget('order_team_counts');
        Cache::forget('active_teams_list');
        Cache::forget('leads_active_group_masters');
    }
}
