<?php

namespace App\Http\Controllers;

use App\Models\PrimeSampleType;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrimeSampleTypeController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $items = PrimeSampleType::withCount('samples')->orderBy('name')->get();

        return view('prime-samples.taxonomy-index', [
            'items' => $items,
            'pageTitle' => 'Prime Sample Types',
            'itemLabel' => 'Type',
            'routePrefix' => 'prime-sample-types',
        ]);
    }

    public function store(Request $request)
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => trim($request->input('name'))]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:prime_sample_types,name'],
        ]);

        PrimeSampleType::create([
            'name' => trim($data['name']),
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Prime sample type created successfully.');
    }

    public function update(Request $request, PrimeSampleType $primeSampleType)
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => trim($request->input('name'))]);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('prime_sample_types', 'name')->ignore($primeSampleType->id),
            ],
        ]);

        $name = trim($data['name']);
        $primeSampleType->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        return back()->with('success', 'Prime sample type updated successfully.');
    }

    public function destroy(PrimeSampleType $primeSampleType)
    {
        $primeSampleType->delete();

        return back()->with('success', 'Prime sample type deleted successfully.');
    }
}
