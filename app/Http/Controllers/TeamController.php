<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TeamController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(auth()->check() && in_array((int) auth()->user()->role_id, [1, 9]), 403, 'Unauthorized action.');
    }

    /**
     * Recalculate equal percentage for all active teams.
     * E.g., 2 teams = 50% each, 4 teams = 25% each.
     */
    private function recalcTeamPercentages(): void
    {
        $activeTeams = Team::where('is_delete', false)->get();
        $count = $activeTeams->count();
        if ($count === 0) return;

        $equalPct = round(100 / $count, 2);
        Team::where('is_delete', false)->update(['percentage' => $equalPct]);
    }

    private function formatMembersCollection($members, $currentTeamId = null)
    {
        return $members->map(function ($m) use ($currentTeamId) {
            $isAssignedToCurrent = !empty($currentTeamId) && (int) $m->team_id === (int) $currentTeamId;
            $isLocked = !empty($m->team_id) && (int) $m->team_id !== (int) $currentTeamId;
            return [
                'id' => $m->id,
                'name' => $m->name,
                'role_id' => $m->role_id,
                'role_name' => $m->role_id == 9 ? 'Subadmin' : 'Marketing',
                'team_id' => $m->team_id,
                'team_name' => $m->team ? $m->team->team_name : null,
                'is_assigned_to_current' => $isAssignedToCurrent,
                'is_locked' => $isLocked,
            ];
        });
    }

    public function index()
    {
        $this->checkAdmin();

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->orderBy('priority', 'asc')
            ->get();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return view('teams.index', compact('teams', 'members'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,team_name',
            'priority' => 'nullable|numeric',
            'member_ids' => 'nullable|array',
        ]);

        $priority = !empty($request->priority) ? (int) $request->priority : ((int) Team::max('priority') + 1);

        $team = Team::create([
            'team_name' => $request->team_name,
            'priority' => $priority,
            'percentage' => 0, // will be recalculated
            'created_by' => auth()->id(),
        ]);

        if ($request->member_ids) {
            User::whereIn('id', $request->member_ids)
                ->where(function ($q) use ($team) {
                    $q->whereNull('team_id')
                      ->orWhere('team_id', $team->id);
                })
                ->update(['team_id' => $team->id]);
        }

        // Recalculate equal percentages for all teams
        $this->recalcTeamPercentages();

        Cache::forget('order_team_counts');

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->orderBy('priority', 'asc')
            ->get();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render(),
            'members' => $this->formatMembersCollection($members, $team->id),
            'new_team' => $team,
            'teams' => $teams,
        ]);
    }

    public function edit($id)
    {
        $this->checkAdmin();

        $team = Team::with('members')->findOrFail($id);

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'id' => $team->id,
            'team_name' => $team->team_name,
            'priority' => $team->priority,
            'percentage' => $team->percentage,
            'member_ids' => $team->members->pluck('id'),
            'members' => $this->formatMembersCollection($members, $team->id),
        ]);
    }

    public function update(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'team_name' => 'required|string|max:255',
            'priority' => 'nullable|numeric',
            'member_ids' => 'nullable|array',
        ]);

        $team = Team::findOrFail($request->team_id);

        $team->update([
            'team_name' => $request->team_name,
            'priority' => !empty($request->priority) ? (int) $request->priority : $team->priority,
        ]);

        // Update member assignments only if member_ids is provided
        if ($request->has('member_ids')) {
            User::where('team_id', $team->id)->update(['team_id' => null]);
            if (!empty($request->member_ids)) {
                User::whereIn('id', $request->member_ids)
                    ->where(function ($q) use ($team) {
                        $q->whereNull('team_id')
                          ->orWhere('team_id', $team->id);
                    })
                    ->update(['team_id' => $team->id]);
            }
        }

        // Recalculate equal percentages for all teams
        $this->recalcTeamPercentages();

        Cache::forget('order_team_counts');

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->orderBy('priority', 'asc')
            ->get();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render(),
            'teams' => $teams,
            'team' => $team,
            'members' => $this->formatMembersCollection($members, $team->id),
        ]);
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $team = Team::with('members')->findOrFail($id);

        // Save current member IDs before removing them
        $team->member_ids = $team->members->pluck('id')->toArray();
        $team->is_delete = true;
        $team->save();

        // Detach members from the team
        User::where('team_id', $team->id)->update(['team_id' => null]);

        // Recalculate equal percentages for remaining active teams
        $this->recalcTeamPercentages();

        Cache::forget('order_team_counts');

        // Refresh team list excluding soft-deleted teams
        $teams = Team::with(['creator', 'members'])
                    ->where('is_delete', false)
                    ->orderBy('priority', 'asc')
                    ->get();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render(),
            'teams' => $teams,
            'members' => $this->formatMembersCollection($members, null),
        ]);
    }

    public function getActiveTeams()
    {
        $this->checkAdmin();

        $teams = Team::where('is_delete', false)
            ->orderBy('priority', 'asc')
            ->get();

        $counts = \App\Models\Order::whereNotNull('uid')
            ->whereNotNull('team_id')
            ->groupBy('team_id')
            ->select('team_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->pluck('total', 'team_id');

        return response()->json([
            'teams' => $teams,
            'counts' => $counts,
        ]);
    }

    public function getUnassignedMembers()
    {
        $this->checkAdmin();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->whereNull('team_id')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'role_id']);

        return response()->json([
            'members' => $members->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->name . ($m->role_id == 9 ? ' (Subadmin)' : ' (Marketing)'),
                ];
            })
        ]);
    }

    public function getMembersTable(Request $request)
    {
        $this->checkAdmin();

        $teamId = $request->get('team_id');
        $query = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name');

        $activeTeam = null;
        if (!empty($teamId) && $teamId !== 'all') {
            $activeTeam = Team::where('is_delete', false)->findOrFail($teamId);
            $query->where('team_id', $activeTeam->id);
        }

        $members = $query->orderBy('name', 'asc')->get();

        $html = view('teams.partials.members_table', [
            'members' => $members,
            'activeTeam' => $activeTeam,
            'teamId' => $teamId ?: 'all',
        ])->render();

        return response()->json([
            'html' => $html,
            'count' => $members->count(),
            'team' => $activeTeam,
        ]);
    }

    public function unassignMember(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::whereIn('role_id', [4, 9])->findOrFail($request->user_id);
        $oldTeamId = $user->team_id;
        $user->update(['team_id' => null]);

        Cache::forget('order_team_counts');

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->orderBy('priority', 'asc')
            ->get();

        $members = User::whereIn('role_id', [4, 9])
            ->where('flag', 0)
            ->with('team:id,team_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Member {$user->name} has been unassigned.",
            'teams' => $teams,
            'members' => $this->formatMembersCollection($members, $oldTeamId),
        ]);
    }
}

