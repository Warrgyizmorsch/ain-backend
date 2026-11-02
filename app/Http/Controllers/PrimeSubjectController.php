<?php

namespace App\Http\Controllers;

use App\Models\PrimeSubject;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrimeSubjectController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $subjects = PrimeSubject::withCount('servicePages')->orderBy('name')->get();

        return view('prime-subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:prime_subjects,name'],
        ]);

        PrimeSubject::create([
            'name'      => $data['name'],
            'slug'      => Str::slug($data['name']),
            'is_active' => true,
        ]);

        return back()->with('success', 'Prime prefix added successfully.');
    }

    public function update(Request $request, PrimeSubject $primeSubject)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:120', Rule::unique('prime_subjects', 'name')->ignore($primeSubject->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $primeSubject->update([
            'name'      => $data['name'],
            'slug'      => Str::slug($data['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Prime prefix updated successfully.');
    }

    public function destroy(PrimeSubject $primeSubject)
    {
        if ($primeSubject->servicePages()->exists()) {
            return back()->with('error', 'This prefix is used by a dynamic page and cannot be deleted.');
        }

        $primeSubject->delete();

        return back()->with('success', 'Prime prefix deleted successfully.');
    }
}

