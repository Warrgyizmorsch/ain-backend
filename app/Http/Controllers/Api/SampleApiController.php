<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use App\Models\SampleCategory;
use Illuminate\Http\Request;

class SampleApiController extends Controller
{
    /**
     * Get paginated list of samples with optional filters.
     */
    public function index(Request $request)
    {
        $limit = intval($request->query('limit', 10));
        // Clamp limit between 1 and 50 to prevent memory exhaustion
        $limit = max(1, min(50, $limit));

        $query = Sample::with(['categotyData', 'type']);

        // Optional search filtering by title
        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Optional filtering by category name or category ID
        if ($request->has('category')) {
            $category = $request->query('category');
            if (is_numeric($category)) {
                $query->where('category', $category);
            } else {
                $query->whereHas('categotyData', function ($q) use ($category) {
                    $q->where('name', $category)
                      ->orWhere('name', 'like', '%' . $category . '%');
                });
            }
        }

        // Optional filtering by type name or type ID
        if ($request->has('type')) {
            $type = $request->query('type');
            if (is_numeric($type)) {
                $query->where('type_id', $type);
            } else {
                $query->whereHas('type', function ($q) use ($type) {
                    $q->where('name', $type)
                      ->orWhere('name', 'like', '%' . $type . '%');
                });
            }
        }

        // Fetch paginated listing (excluding large HTML content field for faster load)
        $samples = $query->latest()
            ->paginate($limit, [
                'id', 'category', 'title', 'slug', 'type_id', 
                'meta_title', 'meta_description', 'created_at'
            ]);

        // Transform the paginated collection to include category and type names directly
        $samples->through(function ($sample) {
            return [
                'id' => $sample->id,
                'title' => $sample->title,
                'slug' => $sample->slug,
                'category_id' => $sample->category,
                'category_name' => $sample->categotyData?->name ?? 'N/A',
                'type_id' => $sample->type_id,
                'type_name' => $sample->type?->name ?? 'N/A',
                'meta_title' => $sample->meta_title,
                'meta_description' => $sample->meta_description,
                'created_at' => $sample->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $samples
        ]);
    }

    /**
     * Get specific sample details by slug.
     */
    public function show($slug)
    {
        $slug = urldecode($slug);

        $sample = Sample::with(['categotyData', 'type'])
            ->where('slug', $slug)
            ->first();

        if (!$sample) {
            return response()->json([
                'success' => false,
                'message' => 'Sample not found'
            ], 404);
        }

        // Append category and type name directly for the detail response
        $sample->category_name = $sample->categotyData?->name ?? 'N/A';
        $sample->type_name = $sample->type?->name ?? 'N/A';

        return response()->json([
            'success' => true,
            'data' => $sample
        ]);
    }

    /**
     * Get all sample categories with sample count.
     */
    public function categories(Request $request)
    {
        $categories = SampleCategory::withCount('Sample')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($cat) {
                return [
                    'id'           => $cat->id,
                    'name'         => $cat->name,
                    'sample_count' => $cat->sample_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}
