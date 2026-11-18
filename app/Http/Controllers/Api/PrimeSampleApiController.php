<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrimeSample;
use App\Models\PrimeSampleCategory;
use App\Models\PrimeSampleType;
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

        $query = PrimeSample::with(['category', 'type'])->latest('id');

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

        if ($request->filled('type')) {
            $type = $request->query('type');
            if (is_numeric($type)) {
                $query->where('type_id', (int) $type);
            } else {
                $query->whereHas('type', function ($q) use ($type) {
                    $q->where('name', $type)
                      ->orWhere('slug', $type);
                });
            }
        }

        $samples = $query->paginate($limit, [
            'id', 'category_id', 'type_id', 'title', 'slug', 'pages_count',
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
                'type_id'       => $sample->type_id,
                'type_name'     => $sample->type?->name ?? 'N/A',
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

        $sample = PrimeSample::with(['category', 'type'])
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
            $sample = PrimeSample::with(['category', 'type'])
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
        $sample->type_name     = $sample->type?->name ?? 'N/A';

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

    public function types()
    {
        $types = PrimeSampleType::withCount('samples')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($type) {
                return [
                    'id'           => $type->id,
                    'name'         => $type->name,
                    'slug'         => $type->slug,
                    'sample_count' => $type->samples_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $types,
        ]);
    }
}
