<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMaster;

class GroupMasterController extends Controller
{
    public function index()
    {
        $groups = GroupMaster::orderBy('id', 'desc')->get();

        return view('back-end.group-master.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        GroupMaster::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = GroupMaster::findOrFail($id);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        GroupMaster::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Group deleted successfully.');
    }
}