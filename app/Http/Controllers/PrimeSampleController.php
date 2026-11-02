<?php

namespace App\Http\Controllers;

use App\Models\PrimeSample;
use App\Models\PrimeSampleCategory;
use App\Services\PrimeAssignmentSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrimeSampleController extends Controller
{
    public function __construct()
    {
        PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Optimized listing method for Prime Samples.
     */
    public function index(Request $request)
    {
        $query = PrimeSample::query()
            ->select(['id', 'category_id', 'title', 'slug', 'pages_count', 'words_count', 'is_published', 'created_at'])
            ->with(['category' => function ($q) {
                $q->select('id', 'name', 'slug');
            }]);

        // Search by title or slug
        if ($request->filled('search')) {
            $keyword = trim((string) $request->input('search'));
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->input('category_id'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $samples = $query->latest('id')->paginate(25)->withQueryString();

        $categories = PrimeSampleCategory::orderBy('name')->get(['id', 'name']);

        return view('prime-samples.index', compact('samples', 'categories'));
    }

    public function create()
    {
        return $this->form(new PrimeSample());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        PrimeSample::create($data);

        return redirect()->route('prime-samples.index')->with('success', 'Prime sample created successfully.');
    }

    public function edit(PrimeSample $primeSample)
    {
        return $this->form($primeSample);
    }

    public function update(Request $request, PrimeSample $primeSample)
    {
        $data = $this->validated($request, $primeSample);

        $primeSample->update($data);

        return redirect()->route('prime-samples.index')->with('success', 'Prime sample updated successfully.');
    }

    public function destroy(PrimeSample $primeSample)
    {
        $primeSample->delete();

        return back()->with('success', 'Prime sample deleted successfully.');
    }

    public function preview(PrimeSample $primeSample)
    {
        return view('prime-samples.preview', ['sample' => $primeSample->load('category')]);
    }

    private function form(PrimeSample $sample)
    {
        $categories = PrimeSampleCategory::orderBy('name')->get();

        return view('prime-samples.form', compact('sample', 'categories'));
    }

    private function validated(Request $request, ?PrimeSample $sample = null): array
    {
        $slugRule = Rule::unique('prime_samples', 'slug');
        if ($sample) {
            $slugRule->ignore($sample->id);
        }

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:180', $slugRule],
            'category_id'      => ['nullable', 'exists:prime_sample_categories,id'],
            'new_category'     => ['nullable', 'string', 'max:120'],
            'content'          => ['required', 'string'],
            'pages_count'      => ['nullable', 'integer', 'min:1'],
            'words_count'      => ['nullable', 'integer', 'min:1'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['slug'] = Str::slug(trim($data['slug'], '/'), '-');

        // Handle on-the-fly new category creation
        if (!empty($data['new_category'])) {
            $catName = trim($data['new_category']);
            $cat = PrimeSampleCategory::firstOrCreate(
                ['name' => $catName],
                ['slug' => Str::slug($catName)]
            );
            $data['category_id'] = $cat->id;
        }

        unset($data['new_category']);

        $data['pages_count'] = !empty($data['pages_count']) ? (int) $data['pages_count'] : 1;
        $data['words_count'] = !empty($data['words_count']) ? (int) $data['words_count'] : ($data['pages_count'] * 250);
        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }
}

