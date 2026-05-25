<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->get();

        $members = User::where('role_id', 4)
            ->where('flag', 0)
            ->whereNull('team_id')
            ->get();

        return view('teams.index', compact('teams', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,team_name',
            'priority' => 'nullable|numeric',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'member_ids' => 'nullable|array',
        ]);

        // Check if total percentage would exceed 100
        $existingTotal = Team::where('is_delete', false)->sum('percentage');
        $newTotal = $existingTotal + floatval($request->percentage ?? 0);

        if ($newTotal > 100) {
            return response()->json([
                'error' => 'The total percentage across all teams cannot exceed 100%.',
            ], 422);
        }

        $team = Team::create([
            'team_name' => $request->team_name,
            'priority' => $request->priority,
            'percentage' => $request->percentage,
            'created_by' => auth()->id(),
        ]);

        if ($request->member_ids) {
            User::whereIn('id', $request->member_ids)->update(['team_id' => $team->id]);
        }

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->get();

        // ✅ Also get updated unassigned members
        $members = User::where('role_id', 4)
            ->where('flag', 0)
            ->whereNull('team_id')
            ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render(),
            'members' => $members, // ✅ return this too
        ]);
    }


    public function edit($id)
    {
        $team = Team::with('members')->findOrFail($id);

        $members = User::where('role_id', 4)
            ->where('flag', 0)
            ->where(function ($query) use ($team) {
                $query->whereNull('team_id')
                    ->orWhere('team_id', $team->id);
            })
            ->get();

        return response()->json([
            'id' => $team->id,
            'team_name' => $team->team_name,
            'priority' => $team->priority,
            'percentage' => $team->percentage,
            'member_ids' => $team->members->pluck('id'),
            'members' => $members->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->name,
                ];
            })
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'team_name' => 'required|string|max:255',
            'priority' => 'nullable|numeric',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'member_ids' => 'nullable|array',
        ]);

        $team = Team::findOrFail($request->team_id);

        // Percentage validation
        $existingTotal = Team::where('is_delete', false)
            ->where('id', '!=', $team->id) // exclude current team
            ->sum('percentage');

        $newTotal = $existingTotal + floatval($request->percentage ?? 0);

        if ($newTotal > 100) {
            return response()->json([
                'error' => 'The total percentage across all teams cannot exceed 100%.',
            ], 422);
        }

        $team->update([
            'team_name' => $request->team_name,
            'priority' => $request->priority,
            'percentage' => $request->percentage,
        ]);

        // Reset all old members
        User::where('team_id', $team->id)->update(['team_id' => null]);

        // Assign new ones
        if ($request->member_ids) {
            User::whereIn('id', $request->member_ids)->update(['team_id' => $team->id]);
        }

        $teams = Team::with(['creator', 'members'])
            ->where('is_delete', false)
            ->get();
        $members = User::where('role_id', 4)
            ->where('flag', 0)
            ->whereNull('team_id')
            ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render(),
            'members' => $members,
        ]);
    }

    public function destroy($id)
    {
        $team = Team::with('members')->findOrFail($id);

        // Save current member IDs before removing them
        $team->member_ids = $team->members->pluck('id')->toArray();
        $team->is_delete = true;
        $team->save();

        // Detach members from the team
        User::where('team_id', $team->id)->update(['team_id' => null]);

        // Refresh team list excluding soft-deleted teams
        $teams = Team::with(['creator', 'members'])
                    ->where('is_delete', false)
                    ->get();

        return response()->json([
            'view' => view('teams.partials.list', compact('teams'))->render()
        ]);
    }



}
