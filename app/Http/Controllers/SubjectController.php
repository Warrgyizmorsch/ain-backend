<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subjects.index', ['subjects' => Subject::latest()->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120', 'unique:subjects,name']]);
        Subject::create(['name' => $data['name'], 'slug' => Str::slug($data['name']), 'is_active' => true]);
        return back()->with('success', 'Subject added successfully.');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('subjects')->ignore($subject->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $subject->update(['name' => $data['name'], 'slug' => Str::slug($data['name']), 'is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->servicePages()->exists()) {
            return back()->with('error', 'This subject is used by a service page and cannot be deleted.');
        }
        $subject->delete();
        return back()->with('success', 'Subject deleted successfully.');
    }
}
