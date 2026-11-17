<?php

namespace App\Http\Controllers;

use App\Models\PrimeSampleCategory;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PrimeSampleCategoryController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    public function index()
    {
        $items = PrimeSampleCategory::withCount('samples')->orderBy('name')->get();

        return view('prime-samples.taxonomy-index', [
            'items' => $items,
            'pageTitle' => 'Prime Sample Categories',
            'itemLabel' => 'Category',
            'routePrefix' => 'prime-sample-categories',
        ]);
    }

    public function store(Request $request)
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => trim($request->input('name'))]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:prime_sample_categories,name'],
        ]);

        PrimeSampleCategory::create([
            'name' => trim($data['name']),
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Prime sample category created successfully.');
    }

    public function update(Request $request, PrimeSampleCategory $primeSampleCategory)
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => trim($request->input('name'))]);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('prime_sample_categories', 'name')->ignore($primeSampleCategory->id),
            ],
        ]);

        $name = trim($data['name']);
        $primeSampleCategory->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        return back()->with('success', 'Prime sample category updated successfully.');
    }

    public function destroy(PrimeSampleCategory $primeSampleCategory)
    {
        $primeSampleCategory->delete();

        return back()->with('success', 'Prime sample category deleted successfully.');
    }
}
