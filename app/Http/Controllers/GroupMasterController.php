<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMaster;
use App\Models\User;
use Illuminate\Validation\Rule;

class GroupMasterController extends Controller
{
    public function index()
    {
        $groups = GroupMaster::with('users:id,name,email,mobile_no')->withCount('users')->orderBy('id', 'desc')->get();
        $users = User::where('flag', 0)->select('id','name','email','mobile_no')->orderBy('name')->get();
        return view('back-end.group-master.index', compact('groups', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255','unique:group_masters,name'],
            'user_ids' => ['nullable','array'], 'user_ids.*' => ['integer','exists:users,id'],
        ]);

        $group = GroupMaster::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);
        $group->users()->sync($request->input('user_ids', []));

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required','string','max:255',Rule::unique('group_masters','name')->ignore($id)],
            'user_ids' => ['nullable','array'], 'user_ids.*' => ['integer','exists:users,id'],
        ]);

        $group = GroupMaster::findOrFail($id);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);
        $group->users()->sync($request->input('user_ids', []));

        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        GroupMaster::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Group deleted successfully.');
    }
    public function assignUser(Request $request, User $user)
    {
        $data = $request->validate([
            'group_id'=>['nullable','exists:group_masters,id'], 'group_ids'=>['nullable','array'],
            'group_ids.*'=>['integer','exists:group_masters,id'], 'new_group_name'=>['nullable','string','max:255']
        ]);
        $groupIds = $data['group_ids'] ?? [];
        if (!empty($data['group_id'])) $groupIds[] = (int) $data['group_id'];
        if (!empty($data['new_group_name'])) {
            $newGroup = GroupMaster::firstOrCreate(['name'=>trim($data['new_group_name'])], ['status'=>1]);
            $groupIds[] = $newGroup->id;
        }
        $user->groups()->sync(array_values(array_unique($groupIds)));
        $groups = $user->groups()->orderBy('name')->get(['group_masters.id','name']);
        if (!$request->expectsJson()) return back()->with('success', 'User groups updated.');
        return response()->json(['message'=>'User groups updated.','groups'=>$groups]);
    }
}
