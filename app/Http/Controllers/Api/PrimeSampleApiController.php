<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeSample;
use App\Models\PrimeSampleCategory;
use Illuminate\Http\Request;

class PrimeSampleApiController extends Controller
{
    public function __construct()
    {
        \App\Services\PrimeAssignmentSetupService::ensureSetup();
    }

    /**
     * Get paginated list of Prime Samples only (Strictly isolated).
     */
    public function index(Request $request)
    {
        $limit = intval($request->query('limit', 10));
        $limit = max(1, min(50, $limit));

        $query = PrimeSample::with('category')->latest('id');

        // Optional search filtering by title
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Optional category filter by name or id
        if ($request->filled('category')) {
            $category = $request->query('category');
            if (is_numeric($category)) {
                $query->where('category_id', (int) $category);
            } else {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', $category)
                      ->orWhere('slug', $category);
                });
            }
        }

        $samples = $query->paginate($limit, [
            'id', 'category_id', 'title', 'slug', 'pages_count',
            'words_count', 'meta_title', 'meta_description', 'created_at',
        ]);

        $samples->through(function ($sample) {
            $catName = $sample->category?->name ?? 'General';
            return [
                'id'            => $sample->id,
                'title'         => $sample->title,
                'slug'          => $sample->slug,
                'category_id'   => $sample->category_id,
                'category'      => $sample->category_id,
                'category_name' => $catName,
                'type_name'     => $catName,
                'pages_count'   => $sample->pages_count,
                'words_count'   => $sample->words_count,
                'meta_title'    => $sample->meta_title,
                'meta_description' => $sample->meta_description,
                'created_at'    => $sample->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $samples,
        ]);
    }

    /**
     * Get prime sample details by slug (Strictly Prime Samples table).
     */
    public function show($slug)
    {
        $slug = urldecode($slug);
        $cleanSlug = trim($slug, '/');

        $sample = PrimeSample::with('category')
            ->where(function ($query) use ($slug, $cleanSlug) {
                $query->where('slug', $cleanSlug)
                      ->orWhere('slug', '/' . $cleanSlug)
                      ->orWhere('slug', $slug)
                      ->orWhere('slug', 'like', '%/' . $cleanSlug)
                      ->orWhere('slug', 'like', '%' . $cleanSlug . '%');
                if (is_numeric($cleanSlug)) {
                    $query->orWhere('id', (int) $cleanSlug);
                }
            })
            ->first();

        if (!$sample) {
            $sample = PrimeSample::with('category')
                ->where('title', 'like', '%' . $cleanSlug . '%')
                ->first();
        }

        if (!$sample) {
            return response()->json([
                'success' => false,
                'message' => "Prime sample not found for '{$slug}'",
            ], 404);
        }

        $sample->category_name = $sample->category?->name ?? 'General';
        $sample->type_name     = $sample->category_name;

        return response()->json([
            'success' => true,
            'data'    => $sample,
        ]);
    }

    /**
     * Get all prime sample categories with counts.
     */
    public function categories()
    {
        $categories = PrimeSampleCategory::withCount('samples')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($cat) {
                return [
                    'id'           => $cat->id,
                    'name'         => $cat->name,
                    'slug'         => $cat->slug,
                    'sample_count' => $cat->samples_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}
